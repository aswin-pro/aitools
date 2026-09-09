import { Button } from '@/components/ui/button';
import { FileIcon, UploadCloud, X } from 'lucide-react';
import { useState } from 'react';
import { useDropzone } from 'react-dropzone';

type DropzoneProps = {
    t: (key: string, data?: Record<string, number>) => string;
    name: string;
    file: File | null;
    onChange: (file: File | null) => void;
    accept: Record<string, string[]>;
    label?: string;
    maxSize?: number; // KB
};

export function Dropzone({
    t,
    name,
    file,
    onChange,
    accept,
    label = 'Drag & drop a file here',
    maxSize = Number(import.meta.env.VITE_SIZE_LIMIT),
}: DropzoneProps) {
    const [inputKey, setInputKey] = useState(0);

    // input props
    const { getRootProps, getInputProps, isDragActive, inputRef } = useDropzone(
        {
            accept,
            maxFiles: 1,
            maxSize: maxSize * 1024,
            onDrop: ([selectedFile]) => {
                if (!selectedFile) return;

                onChange(selectedFile);

                // Put dropped file into the real form input
                const dt = new DataTransfer();
                dt.items.add(selectedFile);

                if (inputRef.current) {
                    inputRef.current.files = dt.files;
                }
            },
        },
    );
    const inputProps = getInputProps();

    return (
        <div className="space-y-3">
            <div
                {...getRootProps()}
                className={`cursor-pointer rounded-xl border-2 border-dashed py-17.5 text-center transition-colors ${
                    isDragActive
                        ? 'border-primary bg-primary/5'
                        : 'border-border hover:border-primary/60'
                }`}
            >
                {/* hidden input */}
                <input
                    key={inputKey}
                    name={name}
                    {...inputProps}
                />

                {/* icon */}
                <UploadCloud className="mx-auto mb-3 h-8 w-8 text-muted-foreground" />

                {/* label */}
                <p className="font-medium text-muted-foreground">{t(label)}</p>

                {/* max size */}
                <p className="mt-1 text-xs text-muted-foreground">
                    {t('Max {{size}} MB', {
                        size: Math.round(maxSize / 1024),
                    })}
                </p>
            </div>

            {/* uploaded file */}
            {file && (
                <div className="flex items-center justify-between rounded-lg border p-3">
                    <div className="flex items-center gap-3">
                        {/* file icon */}
                        <FileIcon className="h-5 w-5 text-primary" />

                        {/* file name */}
                        <div className="min-w-0">
                            <p className="truncate text-sm font-medium">
                                {t(file.name)}
                            </p>

                            <p className="text-xs text-muted-foreground">
                                {(file.size / 1024 / 1024).toFixed(2)} {t('MB')}
                            </p>
                        </div>
                    </div>

                    {/* button */}
                    <Button
                        type="button"
                        variant="ghost"
                        size="icon"
                        onClick={() => {
                            onChange(null);

                            setInputKey((k) => k + 1);
                        }}
                    >
                        <X className="h-4 w-4" />
                    </Button>
                </div>
            )}
        </div>
    );
}
