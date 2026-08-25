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
    const toolbarRef = useRef<HTMLDivElement>(null);

    const quillRef = useRef<Quill | null>(null);
    const onChangeRef = useRef(onChange);

    useEffect(() => {
        onChangeRef.current = onChange;
    }, [onChange]);

    useEffect(() => {
        if (!editorRef.current || !toolbarRef.current || quillRef.current) {
            return;
        }

        const quill = new Quill(editorRef.current, {
            theme: "snow",

            placeholder: "Write your content...",

            modules: {
                toolbar: toolbarRef.current,
            },
        });

        quillRef.current = quill;

        // Set existing content
        if (value) {
            quill.clipboard.dangerouslyPasteHTML(value);
        }

        // Handle changes
        const handleChange = () => {
            const html = quill.root.innerHTML;

            onChangeRef.current(html === "<p><br></p>" ? "" : html);
        };

        quill.on("text-change", handleChange);

        quill.enable(!disabled);

        return () => {
            quill.off("text-change", handleChange);

            quillRef.current = null;

            if (editorRef.current) {
                editorRef.current.innerHTML = "";
            }

            if (toolbarRef.current) {
                toolbarRef.current.innerHTML = "";
            }
        };
    }, []);

    // Update content when editing
    useEffect(() => {
        const quill = quillRef.current;

        if (!quill) {
            return;
        }

        const currentHtml = quill.root.innerHTML;

        if (value !== currentHtml) {
            quill.clipboard.dangerouslyPasteHTML(value || "");
        }
    }, [value]);

    // Enable / disable
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
                    error ? "border-destructive" : "border-input"
                }`}
            >
                {/* Quill toolbar */}
                <div ref={toolbarRef} className="ql-toolbar ql-snow">
                    <span className="ql-formats">
                        <select className="ql-header">
                            <option value="">Paragraph</option>

                            <option value="1">Heading 1</option>

                            <option value="2">Heading 2</option>

                            <option value="3">Heading 3</option>

                            <option value="4">Heading 4</option>

                            <option value="5">Heading 5</option>

                            <option value="6">Heading 6</option>

                        </select>
                    </span>

                    <span className="ql-formats">
                        <button type="button" className="ql-bold" />

                        <button type="button" className="ql-italic" />

                        <button type="button" className="ql-underline" />

                        <button type="button" className="ql-strike" />
                    </span>

                    <span className="ql-formats">
                        <select className="ql-color" />

                        <select className="ql-background" />
                    </span>

                    <span className="ql-formats">
                        <button type="button" className="ql-align" />

                        <button
                            type="button"
                            className="ql-align"
                            value="center"
                        />

                        <button
                            type="button"
                            className="ql-align"
                            value="right"
                        />
                    </span>

                    <span className="ql-formats">
                        <button
                            type="button"
                            className="ql-list"
                            value="ordered"
                        />

                        <button
                            type="button"
                            className="ql-list"
                            value="bullet"
                        />
                    </span>

                    <span className="ql-formats">
                        <button
                            type="button"
                            className="ql-indent"
                            value="-1"
                        />

                        <button
                            type="button"
                            className="ql-indent"
                            value="+1"
                        />
                    </span>

                    <span className="ql-formats">
                        <button type="button" className="ql-blockquote" />

                        <button type="button" className="ql-code-block" />
                    </span>

                    <span className="ql-formats">
                        <button type="button" className="ql-link" />

                        <button type="button" className="ql-clean" />
                    </span>
                </div>

                {/* Editor */}
                <div ref={editorRef} className="min-h-[400px]" />
            </div>

            <InputError message={error} />
        </div>
    );
}
