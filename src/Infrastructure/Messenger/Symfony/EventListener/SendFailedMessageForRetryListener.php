<?php

namespace Untek\Framework\Messenger\Infrastructure\Messenger\Symfony\EventListener;

use Untek\Framework\Messenger\Infrastructure\Messenger\Symfony\Stamp\TopicStamp;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Transport\Sender\SendersLocator;

class SendFailedMessageForRetryListener implements EventSubscriberInterface
{

    public function __construct(
        private SendersLocator $sendersLocator,
        private EventDispatcherInterface $eventDispatcher,
        private array $messagesClasses
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // must have higher priority than SendFailedMessageToFailureTransportListener
            WorkerMessageFailedEvent::class => ['onMessageFailed', 100],
        ];
    }

    public function onMessageFailed(WorkerMessageFailedEvent $event)
    {
        $envelope = $event->getEnvelope();
        if (!$this->isSupported($envelope)) {
            return;
        }
        $retryEnvelop = $this->makeEnvelopeForRetry($envelope);
        $senders = $this->sendersLocator->getSenders($envelope);
        foreach ($senders as $alias => $sender) {
            $sender->send($retryEnvelop);
        }
    }

    private function makeEnvelopeForRetry(Envelope $envelope): Envelope
    {
        $stamps = $envelope->all();
        unset($stamps[TopicStamp::class]);
        $stampObjects = $this->stampMapToFlat($stamps);
        $stampObjects[] = $this->getTopicStampForRetry($envelope);
        return new Envelope($envelope->getMessage(), $stampObjects);
    }

    private function getTopicStampForRetry(Envelope $envelope): TopicStamp
    {
        /** @var TopicStamp $topicStamp */
        $topicStamp = $envelope->last(TopicStamp::class);
        $topic = $topicStamp->getTopic();
        $retryTopic = $this->getTopicForRetry($topic);
        return new TopicStamp($retryTopic);
    }

    private function stampMapToFlat($stamps)
    {
        $stampObjects = [];
        foreach ($stamps as $stampList) {
            foreach ($stampList as $stamp) {
                $stampObjects[] = $stamp;
            }
        }
        return $stampObjects;
    }

    private function isSupported(Envelope $envelope): bool
    {
        $messageClass = get_class($envelope->getMessage());
        return in_array($messageClass, $this->messagesClasses);
    }

    private function getTopicForRetry(string $topic): string
    {
        $isMatch = preg_match('/(.+-attempt-)(\d+)/i', $topic, $matches);
        if ($isMatch) {
            $attemptNumber = intval($matches[2]);
            $attemptTopic = $matches[1] . ($attemptNumber + 1);
        } else {
            $attemptTopic = $topic . '-attempt-2';
        }
        return $attemptTopic;
    }
}
