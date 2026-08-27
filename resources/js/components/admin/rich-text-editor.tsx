import { useEffect, useRef } from "react";
import Quill from "quill";

import "quill/dist/quill.snow.css";

import { Label } from "@/components/ui/label";
import InputError from "@/components/input-error";

interface RichTextEditorProps {
    label: string;
    value: string;
    onChange: (value: string) => void;
    error?: string;
    required?: boolean;
    disabled?: boolean;
    id?: string;
}

export default function RichTextEditor({
    label,
    value,
    onChange,
    error,
    required = false,
    disabled = false,
    id = "rich-text-editor",
}: RichTextEditorProps) {
    const editorRef = useRef<HTMLDivElement>(null);
    const quillRef = useRef<Quill | null>(null);

    const onChangeRef = useRef(onChange);
    const isSettingContent = useRef(false);

    /*
     * Keep latest onChange
     */
    useEffect(() => {
        onChangeRef.current = onChange;
    }, [onChange]);

    /*
     * Initialize Quill
     */
    useEffect(() => {
        if (!editorRef.current || quillRef.current) {
            return;
        }

        const quill = new Quill(editorRef.current, {
            theme: "snow",

            placeholder: "Write your content...",

            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],

                    [
                        "bold",
                        "italic",
                        "underline",
                        "strike",
                    ],

                    [{ align: [] }],

                    [
                        {
                            list: "ordered",
                        },
                        {
                            list: "bullet",
                        },
                    ],

                    ["blockquote"],

                    ["link"],

                    ["clean"],
                ],
            },
        });

        quillRef.current = quill;

        /*
         * Set initial value
         */
        if (value) {
            isSettingContent.current = true;

            quill.clipboard.dangerouslyPasteHTML(value);

            isSettingContent.current = false;
        }

        /*
         * Handle changes
         */
        const handleChange = () => {
            if (isSettingContent.current) {
                return;
            }

            const html = quill.root.innerHTML;

            onChangeRef.current(
                html === "<p><br></p>" ? "" : html,
            );
        };

        quill.on("text-change", handleChange);

        /*
         * Enable / disable
         */
        quill.enable(!disabled);

        /*
         * Cleanup
         */
        return () => {
            quill.off("text-change", handleChange);

            quillRef.current = null;

            if (editorRef.current) {
                editorRef.current.innerHTML = "";
            }
        };
    }, []);

    /*
     * Update content when value changes.
     *
     * Important for Edit Blog.
     */
    useEffect(() => {
        const quill = quillRef.current;

        if (!quill) {
            return;
        }

        const currentHtml = quill.root.innerHTML;

        if (value === currentHtml) {
            return;
        }

        isSettingContent.current = true;

        quill.clipboard.dangerouslyPasteHTML(
            value || "",
        );

        isSettingContent.current = false;
    }, [value]);

    /*
     * Enable / disable
     */
    useEffect(() => {
        quillRef.current?.enable(!disabled);
    }, [disabled]);

    return (
        <div className="grid gap-2">
            <Label htmlFor={id} required={required}>
                {label}
            </Label>

            <div
                className={`overflow-hidden rounded-md border ${
                    error
                        ? "border-destructive"
                        : "border-input"
                }`}
            >
                <div
                    ref={editorRef}
                    className="min-h-[300px]"
                />
            </div>

            <InputError message={error} />
        </div>
    );
}