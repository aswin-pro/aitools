'use client';

import { Download, Pause, Play, Volume2 } from 'lucide-react';
import { useEffect, useRef, useState } from 'react';
import { Button } from './button';
import { Progress } from './progress';
import { Slider } from './slider';

export function AudioPlayer({
    t,
    name,
    src,
}: {
    t: (key: string) => string;
    name: string;
    src: string;
}) {
    // audio reference
    const audioRef = useRef<HTMLAudioElement>(null);

    // states
    const [playing, setPlaying] = useState(false);
    const [currentTime, setCurrentTime] = useState(0);
    const [duration, setDuration] = useState(0);
    const [volume, setVolume] = useState([100]);

    // audio events
    useEffect(() => {
        const audio = audioRef.current;
        if (!audio) return;

        audio.onloadedmetadata = () => setDuration(audio.duration);
        audio.ontimeupdate = () => setCurrentTime(audio.currentTime);
        audio.onended = () => setPlaying(false);
    }, []);

    // Play / Pause
    const togglePlay = () => {
        const audio = audioRef.current;
        if (!audio) return;

        if (audio.paused) {
            audio.play();
            setPlaying(true);
        } else {
            audio.pause();
            setPlaying(false);
        }
    };

    // Volume
    const changeVolume = (value: number[]) => {
        const audio = audioRef.current;
        if (!audio) return;

        audio.volume = value[0] / 100;
        setVolume(value);
    };

    // format
    const format = (time: number) =>
        `${Math.floor(time / 60)}:${Math.floor(time % 60)
            .toString()
            .padStart(2, '0')}`;

    return (
        <div className="space-y-5 rounded-2xl border p-5">
            {/* audio */}
            <audio ref={audioRef} src={src} preload="metadata" />

            {/* Header */}
            <div className="flex items-center justify-between">
                <div>
                    <h4 className="max-w-40 truncate font-semibold">
                        {t(name)}
                    </h4>
                    <p className="text-sm text-muted-foreground">
                        <span className="uppercase">
                            {src.split('.').pop()}
                        </span>{' '}
                        {t('Audio')}
                    </p>
                </div>

                {/* Download */}
                <Button type="button" variant="outline" size="icon" asChild>
                    <a href={src} download>
                        <Download className="h-4 w-4" />
                    </a>
                </Button>
            </div>

            {/* Read-only progress */}
            <div className="space-y-2">
                {/* Progress */}
                <Progress value={(currentTime / (duration || 1)) * 100} />

                {/* Timer */}
                <div className="flex justify-between text-xs text-muted-foreground">
                    <span>{format(currentTime)}</span>
                    <span>{format(duration)}</span>
                </div>
            </div>

            {/* Controls */}
            <div className="flex items-center justify-between">
                <Button
                    type="button"
                    size="icon"
                    className="h-10 w-10 rounded-full"
                    onClick={togglePlay}
                >
                    {playing ? (
                        <Pause className="h-5 w-5 fill-white" />
                    ) : (
                        <Play className="h-5 w-5 fill-white" />
                    )}
                </Button>

                {/* Volume */}
                <div className="flex w-40 items-center gap-3">
                    <Volume2 className="h-4 w-4 text-muted-foreground" />

                    <Slider
                        value={volume}
                        max={100}
                        step={1}
                        onValueChange={changeVolume}
                    />
                </div>
            </div>
        </div>
    );
}
