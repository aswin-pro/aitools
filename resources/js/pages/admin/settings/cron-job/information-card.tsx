import { Info } from "lucide-react";
import { useTranslation } from "react-i18next";


export default function InformationCard() {

    const { t } = useTranslation();

    return (
        <div className="rounded-lg border bg-muted/40 p-4">
            <div className="flex gap-3">
                <Info className="mt-0.5 size-4 shrink-0 text-muted-foreground" />

                <div className="space-y-2 text-sm">
                    <p className="font-medium">
                        {t("How reminder dates work")}
                    </p>

                    <p className="text-muted-foreground">
                        {t(
                            "Enter comma-separated day values relative to today.",
                        )}
                    </p>

                    <div className="grid gap-1 text-muted-foreground">
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

                    <p className="text-sm text-muted-foreground">
                        {t("Allowed range")}:{" "}
                        <code className="font-mono">-30</code> {t("to")}{" "}
                        <code className="font-mono">366</code> {t("days")}.
                    </p>

                    <p className="text-sm text-muted-foreground">
                        {t("Example")}:{" "}
                        <code className="rounded bg-background px-1.5 py-0.5 font-mono">
                            30,10,3,1,0,-1
                        </code>
                    </p>
                </div>
            </div>
        </div>
    );
}
