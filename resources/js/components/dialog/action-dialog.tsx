import { useTranslation } from "react-i18next";
import { Button } from "../ui/button";
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "../ui/dialog";
import { LoadingSwap } from "../ui/loading-swap";

export const ActionDialog = ({
    dialogOpen,
    setDialogOpen,
    title,
    description,
    handleAction,
    children,
    loading
}: {
    dialogOpen: boolean;
    setDialogOpen: (open: boolean) => void;
    title: string;
    description: string;
    handleAction: () => void;
    children?: React.ReactNode;
    loading: boolean;
}) => {
    const { t } = useTranslation();
    return (
        <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
            {/* Dialog Content */}
            <DialogContent className="sm:max-w-md">
                {/* Dialog Header */}
                <DialogHeader>
                    {/* Dialog Title */}
                    <DialogTitle>{t(title)}</DialogTitle>
                    {/* Dialog Description */}
                    <DialogDescription>{t(description)}</DialogDescription>
                </DialogHeader>

                {children && <div>{children}</div>}

                {/* Dialog Footer */}
                <DialogFooter className="gap-2">
                    {/* Dialog Close */}
                    <DialogClose asChild>
                        <Button type="button" variant="outline">
                            {t("Cancel")}
                        </Button>
                    </DialogClose>
                    {/* Dialog Ok */}
                    <Button
                        type="button"
                        variant="default"
                        disabled={loading}
                        onClick={() => {                            
                            handleAction();
                        }}
                    >
                        <LoadingSwap isLoading={loading}>{t("Confirm")}</LoadingSwap>
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
};
