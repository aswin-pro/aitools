import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Info } from "lucide-react";
import { useTranslation } from "react-i18next";

export default function InformationCard() {
    const { t } = useTranslation();

    return (
        <Alert>
            <Info className="size-4" />

            <AlertTitle>{t("How reminder dates work")}</AlertTitle>

            <AlertDescription>
                <div className="space-y-2">
                    <p>
                        {t(
                            "Enter comma-separated day values relative to today.",
                        )}
                    </p>

                    <div className="grid gap-1 bg-green-100 p-5 rounded-2xl w-max">
                        <p>
                            <code className="rounded bg-background px-1.5 py-0.5 font-mono text-xs">
                                30
                            </code>{" "}
                            → {t("Plans expiring in 30 days")}
                        </p>

                        <p>
                            <code className="rounded bg-background px-1.5 py-0.5 font-mono text-xs">
                                0
                            </code>{" "}
                            → {t("Plans expiring today")}
                        </p>

                        <p>
                            <code className="rounded bg-background px-1.5 py-0.5 font-mono text-xs">
                                -10
                            </code>{" "}
                            → {t("Plans that expired 10 days ago")}
                        </p>
                    </div>

                    <p>
                        {t("Allowed range")}:{" "}
                        <code className="rounded bg-background px-1.5 py-0.5 font-mono text-xs">
                            -30
                        </code>{" "}
                        {t("to")}{" "}
                        <code className="rounded bg-background px-1.5 py-0.5 font-mono text-xs">
                            366
                        </code>{" "}
                        {t("days")}.
                    </p>

                    <p>
                        {t("Example")}:{" "}
                        <code className="rounded bg-background px-1.5 py-0.5 font-mono text-xs">
                            30,10,3,1,0,-1
                        </code>
                    </p>
                </div>
            </AlertDescription>
        </Alert>
    );
}
