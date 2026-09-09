import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '../ui/dialog';

export const BasicDialog = ({
    t,
    dialogOpen,
    setDialogOpen,
    title,
    description,
    children,
    size,
}: {
    t: (key: string) => string;
    dialogOpen: boolean;
    setDialogOpen: (open: boolean) => void;
    title: string;
    description: string;
    children?: React.ReactNode;
    size?: string;
}) => {
    return (
        <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
            {/* Dialog Content */}
            <DialogContent className={size ? size : 'sm:max-w-md'}>
                {/* Dialog Header */}
                <DialogHeader>
                    {/* Dialog Title */}
                    <DialogTitle>{t(title)}</DialogTitle>
                    {/* Dialog Description */}
                    <DialogDescription>{t(description)}</DialogDescription>
                </DialogHeader>

                {children && (
                    <div className="max-h-[60vh] overflow-y-auto p-0.5">
                        {children}
                    </div>
                )}
            </DialogContent>
        </Dialog>
    );
};
