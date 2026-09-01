import { useTranslation } from "react-i18next";

interface Shortcode {
    code: string;
    label: string;
}

interface Props {
    shortcodes: Shortcode[];
}

export default function ShortcodeTable({ shortcodes }: Props) {
    const { t } = useTranslation();

    return (
        <div className="space-y-3">
            <h3 className="text-sm font-medium">
                {t("Short codes/Variables")}
            </h3>

            <div className="overflow-hidden rounded-md border">
                <table className="w-full text-sm">
                    <thead className="border-b bg-muted/50">
                        <tr>
                            <th className="px-4 py-3 text-left font-medium">
                                {t("Short Code")}
                            </th>
                            <th className="px-4 py-3 text-left font-medium">
                                {t("Value")}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        {shortcodes.map((shortcode) => (
                            <tr
                                key={shortcode.code}
                                className="border-b last:border-0"
                            >
                                <td className="px-4 py-3 font-mono text-xs">
                                    {shortcode.code}
                                </td>

                                <td className="px-4 py-3 text-muted-foreground">
                                    {t(shortcode.label)}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}