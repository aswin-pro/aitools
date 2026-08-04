import { useTranslation } from "react-i18next";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from "../ui/dialog";

export const BasicDialog = ({
    dialogOpen,
    setDialogOpen,
    title,
    description,
    children,
    size,
}: {
    dialogOpen: boolean;
    setDialogOpen: (open: boolean) => void;
    title: string;
    description: string;
    children?: React.ReactNode;
    size?: string;
}) => {
    const { t } = useTranslation();
    return (
        <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
            {/* Dialog Content */}
            <DialogContent className={size ? size : "sm:max-w-md"}>
                {/* Dialog Header */}
                <DialogHeader>
                    {/* Dialog Title */}
                    <DialogTitle>{t(title)}</DialogTitle>
                    {/* Dialog Description */}
                    <DialogDescription>{t(description)}</DialogDescription>
                </DialogHeader>

                {children && <div className="overflow-y-auto max-h-[60vh] p-0.5">{children}</div>}
            </DialogContent>
        </Dialog>
    );
};
