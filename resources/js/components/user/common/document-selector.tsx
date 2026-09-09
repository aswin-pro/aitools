import { ActionDialog } from '@/components/dialog/action-dialog';
import { BasicDialog } from '@/components/dialog/dialog';
import { Button } from '@/components/ui/button';
import {
    Field,
    FieldContent,
    FieldDescription,
    FieldLabel,
    FieldTitle,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Skeleton } from '@/components/ui/skeleton';
import { UserUpload } from '@/types/user';
import axios from 'axios';
import { Trash2 } from 'lucide-react';
import { useEffect, useState } from 'react';
import { toast } from 'sonner';

export default function DocumentSelector({
    t,
    dialogOpen,
    setDialogOpen,
    file,
    onFileChange,
}: {
    t: (key: string) => string;
    dialogOpen: boolean;
    setDialogOpen: (dialogOpen: boolean) => void;
    file: string | null;
    onFileChange: (file: string | null) => void;
}) {
    const [uploads, setUploads] = useState<UserUpload[]>([]);
    const [loading, setLoading] = useState<boolean>(false);
    const [deleteDialogOpen, setDeleteDialogOpen] = useState<boolean>(false);
    const [fileId, setFileId] = useState<string | null>(null);
    const [deleteLoading, setDeleteLoading] = useState<boolean>(false);

    useEffect(() => {
        if (!dialogOpen || uploads.length) return;

        let cancelled = false;

        const fetchUploads = async () => {
            setLoading(true);

            try {
                const res = await axios.get(
                    route('dashboard.user.user-uploads.index'),
                );

                if (!cancelled) {
                    setUploads(res.data.uploads);
                }
            } finally {
                if (!cancelled) {
                    setLoading(false);
                }
            }
        };

        fetchUploads();

        return () => {
            cancelled = true;
        };
    }, [dialogOpen]);

    // Upload a single file to the server
    const uploadFile = (file: File) => {
        // form data
        const formData = new FormData();
        formData.append('file', file);

        // post
        return axios.post(
            route('dashboard.user.user-uploads.upload'),
            formData,
        );
    };

    // Handle file selection from input
    const handleUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
        // check files
        if (!e.target.files?.length) return;
        // set loading
        setLoading(true);
        // upload all files
        Promise.all(
            Array.from(e.target.files).map((file) =>
                uploadFile(file)
                    .then((res) => {
                        setUploads((prev) => [res.data.upload, ...prev]);
                    })
                    .catch((err) => {
                        toast.error(
                            t(err.response?.data?.message || 'Upload failed!'),
                        );
                    }),
            ),
        ).finally(() => {
            setLoading(false);
            e.target.value = '';
        });
    };

    // Handle drag-and-drop file upload
    const handleDrop = (e: React.DragEvent<HTMLLabelElement>) => {
        // prevent default
        e.preventDefault();
        // check files
        if (!e.dataTransfer.files.length) return;
        // set loading
        setLoading(true);
        // upload all files
        Promise.all(
            Array.from(e.dataTransfer.files).map((file) =>
                uploadFile(file)
                    .then((res) => {
                        setUploads((prev) => [res.data.upload, ...prev]);
                    })
                    .catch((err) => {
                        toast.error(
                            t(err.response?.data?.message || 'Upload failed!'),
                        );
                    }),
            ),
        ).finally(() => {
            setLoading(false);
        });
    };

    // Prevent default browser drag behavior
    const handleDragOver = (e: React.DragEvent<HTMLLabelElement>) => {
        e.preventDefault();
    };

    // Delete upload
    const deleteUpload = () => {
        setDeleteLoading(true);

        axios
            .delete(
                route('dashboard.user.user-uploads.destroy', {
                    id: fileId,
                }),
            )
            .then(() => {
                setDeleteLoading(false);
                setDeleteDialogOpen(false);
                setFileId(null);
                onFileChange(null);
                setUploads((prev) =>
                    prev.filter((item) => item.upload_id !== fileId),
                );
                toast.success(t('File deleted successfully!'));
            })
            .catch((err) => {
                setDeleteLoading(false);
                toast.error(
                    t(err.response?.data?.message || 'File deletion failed!'),
                );
            });
    };

    return (
        <BasicDialog
            t={t}
            dialogOpen={dialogOpen}
            setDialogOpen={setDialogOpen}
            title="Choose Document"
            description="Upload documents from your device and select it."
            size="sm:max-w-3xl"
        >
            <div className="space-y-5">
                {/* Upload Area */}
                <label
                    className="flex h-32 w-full cursor-pointer items-center justify-center rounded-md border border-dashed text-sm text-muted-foreground"
                    onDrop={handleDrop}
                    onDragOver={handleDragOver}
                >
                    {t('Click to upload or drag and drop')}
                    <Input
                        type="file"
                        accept=".pdf,.doc,.docx,.txt,.md,.rtf,.odt,.csv,.xls,.xlsx,.ppt,.pptx,.json,.xml,.html"
                        multiple
                        className="hidden"
                        onChange={handleUpload}
                    />
                </label>

                <div className="pb-1">
                    {/* Skeleton */}
                    {loading &&
                        Array.from({ length: 4 }).map((_, i) => (
                            <Skeleton key={i} className="h-14 w-full" />
                        ))}

                    {/* Uploads */}
                    {!loading && (
                        <RadioGroup
                            defaultValue={file ?? ''}
                            onValueChange={onFileChange}
                            className="grid grid-cols-1 gap-4 md:grid-cols-2"
                        >
                            {uploads.map((item) => (
                                <FieldLabel
                                    key={item.id}
                                    htmlFor={item.upload_id}
                                    onClick={() => {
                                        onFileChange(item.upload_id);
                                        setDialogOpen(false);
                                    }}
                                >
                                    <Field orientation="horizontal">
                                        <FieldContent>
                                            <FieldTitle>
                                                <span className="max-w-52 truncate">
                                                    {item.file_name}
                                                </span>
                                            </FieldTitle>

                                            <FieldDescription className="text-xs">
                                                {item.formatted_created_at}
                                            </FieldDescription>

                                            <Button
                                                type="button"
                                                variant="destructive"
                                                size="xs"
                                                onClick={(e) => {
                                                    e.stopPropagation();
                                                    setFileId(item.upload_id);
                                                    setDeleteDialogOpen(true);
                                                }}
                                                className="mt-3 w-18 rounded-sm!"
                                            >
                                                <Trash2 />
                                                {t('Delete')}
                                            </Button>
                                        </FieldContent>
                                        <RadioGroupItem
                                            id={item.upload_id}
                                            value={item.upload_id}
                                        />
                                    </Field>
                                </FieldLabel>
                            ))}
                        </RadioGroup>
                    )}
                </div>
            </div>

            {/* Delete dialog */}
            <ActionDialog
                t={t}
                dialogOpen={deleteDialogOpen}
                setDialogOpen={setDeleteDialogOpen}
                title="Delete File"
                description="Are you sure you want to delete this file?"
                handleAction={deleteUpload}
                loading={deleteLoading}
            />
        </BasicDialog>
    );
}
