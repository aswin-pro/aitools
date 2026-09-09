import { Bubble, BubbleContent } from '@/components/ui/bubble';
import { Button } from '@/components/ui/button';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupButton,
} from '@/components/ui/input-group';
import { Marker, MarkerContent } from '@/components/ui/marker';
import { Message, MessageContent } from '@/components/ui/message';
import {
    MessageScroller,
    MessageScrollerButton,
    MessageScrollerContent,
    MessageScrollerItem,
    MessageScrollerProvider,
    MessageScrollerViewport,
} from '@/components/ui/message-scroller';
import { Textarea } from '@/components/ui/textarea';
import { ChatMessage, SpeechRecognition } from '@/types/user';
import { ArrowUpIcon, Copy, Mic, MicOff } from 'lucide-react';
import { ReactNode, useEffect, useRef, useState } from 'react';
import { toast } from 'sonner';
import { RenderMdContent } from '../common/render-md-content';

export function ChatMessages({
    t,
    typingLabel,
    emptyState,
    messages,
    input,
    sending,
    onInputChange,
    onSend,
    composerExtra,
}: {
    t: (key: string) => string;
    typingLabel: ReactNode;
    emptyState: ReactNode;
    messages: ChatMessage[];
    input: string;
    sending: boolean;
    onInputChange: (value: string) => void;
    onSend: () => void;
    composerExtra?: ReactNode;
}) {
    // states
    const [listening, setListening] = useState(false);

    // refs
    const recognitionRef = useRef<SpeechRecognition | null>(null);
    const finalTranscriptRef = useRef('');
    const SpeechRecognition =
        window.SpeechRecognition || window.webkitSpeechRecognition;

    // check speech is supported by browser
    const isSpeechSupported = Boolean(SpeechRecognition);

    // speech toggle
    const toggleSpeech = () => {
        if (!recognitionRef.current) return;

        if (listening) {
            recognitionRef.current.stop();
        } else {
            recognitionRef.current.start();
        }
    };

    // speech recognition
    useEffect(() => {
        const SpeechRecognition =
            window.SpeechRecognition ?? window.webkitSpeechRecognition;

        if (!SpeechRecognition) return;

        const recognition = new SpeechRecognition();

        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.maxAlternatives = 1;
        recognition.lang = navigator.language || 'en-US';

        recognition.onstart = () => {
            setListening(true);
            finalTranscriptRef.current = input ? `${input} ` : '';
        };

        recognition.onresult = (event) => {
            let interim = '';

            for (let i = event.resultIndex; i < event.results.length; i++) {
                const result = event.results[i];

                if (result.isFinal) {
                    finalTranscriptRef.current += result[0].transcript + ' ';
                } else {
                    interim += result[0].transcript;
                }
            }

            onInputChange(finalTranscriptRef.current + interim);
        };

        recognition.onend = () => setListening(false);

        recognitionRef.current = recognition;

        return () => recognition.stop();
    }, [input, onInputChange]);

    return (
        <MessageScrollerProvider
            autoScroll
            defaultScrollPosition="end"
            scrollPreviousItemPeek={0}
            scrollMargin={8}
        >
            <MessageScroller className="min-h-0 flex-1">
                <MessageScrollerViewport>
                    <MessageScrollerContent className="px-6 py-6">
                        {messages.length === 0
                            ? emptyState
                            : messages.map((message) => {
                                  const isUser =
                                      message.responsed_by === 'user';

                                  return (
                                      <MessageScrollerItem
                                          key={message.chat_message_id}
                                          messageId={message.chat_message_id}
                                      >
                                          <Message
                                              align={isUser ? 'end' : 'start'}
                                          >
                                              <MessageContent>
                                                  <Bubble
                                                      variant={
                                                          isUser
                                                              ? 'tinted'
                                                              : 'ghost'
                                                      }
                                                      className="w-fit"
                                                  >
                                                      <BubbleContent className="wrap-break-word">
                                                          {isUser ? (
                                                              <div className="whitespace-pre-wrap">
                                                                  {
                                                                      message.chat_message
                                                                  }
                                                              </div>
                                                          ) : (
                                                              <div>
                                                                  <RenderMdContent
                                                                      content={
                                                                          message.chat_message
                                                                      }
                                                                  />

                                                                  <div className="flex items-center gap-1">
                                                                      <Button
                                                                          type="button"
                                                                          variant="ghost"
                                                                          size="icon"
                                                                          className="-mt-1 h-7 w-7"
                                                                          onClick={() => {
                                                                              navigator.clipboard.writeText(
                                                                                  message.chat_message,
                                                                              );
                                                                              toast.success(
                                                                                  t(
                                                                                      'Copied to clipboard',
                                                                                  ),
                                                                              );
                                                                          }}
                                                                      >
                                                                          <Copy />
                                                                      </Button>

                                                                      <p className="text-xs text-muted-foreground">
                                                                          {
                                                                              message.formatted_created_at
                                                                          }
                                                                      </p>
                                                                  </div>
                                                              </div>
                                                          )}
                                                      </BubbleContent>
                                                  </Bubble>
                                              </MessageContent>
                                          </Message>
                                      </MessageScrollerItem>
                                  );
                              })}

                        {sending && (
                            <MessageScrollerItem messageId="typing-indicator">
                                <Marker role="status">
                                    <MarkerContent className="shimmer">
                                        {typingLabel}
                                    </MarkerContent>
                                </Marker>
                            </MessageScrollerItem>
                        )}
                    </MessageScrollerContent>
                </MessageScrollerViewport>

                <MessageScrollerButton variant="outline" />
            </MessageScroller>

            <div className="border-t p-3">
                <InputGroup>
                    <Textarea
                        value={input}
                        onChange={(e) => onInputChange(e.target.value)}
                        onKeyDown={(e) => {
                            if (e.key === 'Enter' && !e.shiftKey) {
                                e.preventDefault();
                                onSend();
                            }
                        }}
                        placeholder={t('Type your message here')}
                        className="max-h-56 min-h-16 resize-none border-0 bg-transparent! shadow-none focus-visible:ring-0"
                        autoFocus
                    />

                    <InputGroupAddon
                        align="block-end"
                        className={
                            composerExtra
                                ? 'flex justify-between'
                                : 'flex justify-end'
                        }
                    >
                        {composerExtra}

                        <div className="flex gap-2">
                            <InputGroupButton
                                type="button"
                                variant="outline"
                                size="icon-sm"
                                className="rounded-full"
                                disabled={!isSpeechSupported}
                                onClick={toggleSpeech}
                            >
                                {listening ? <MicOff /> : <Mic />}
                            </InputGroupButton>

                            <InputGroupButton
                                type="button"
                                variant="default"
                                size="icon-sm"
                                className="rounded-full"
                                disabled={sending || !input.trim()}
                                onClick={onSend}
                            >
                                <ArrowUpIcon />
                            </InputGroupButton>
                        </div>
                    </InputGroupAddon>
                </InputGroup>
            </div>
        </MessageScrollerProvider>
    );
}
