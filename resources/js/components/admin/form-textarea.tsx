import { forwardRef, type ComponentProps } from "react";

import { Textarea } from "@/components/ui/textarea";
import { Label } from "@/components/ui/label";
import InputError from "@/components/input-error";

interface FormTextareaProps extends ComponentProps<typeof Textarea> {
    label: string;
    error?: string;
    required?: boolean;
    subLable?: string;
    containerClassName?: string;
}

const FormTextarea = forwardRef<HTMLTextAreaElement, FormTextareaProps>(
    (
        {
            label,
            error,
            required = false,
            containerClassName = "",
            subLable,
            ...props
        },
        ref,
    ) => {
        return (
            <div className={`grid gap-2 ${containerClassName}`}>
                <div className="flex items-center gap-2">
                    <Label htmlFor={props.id} required={required}>
                        {label}
                    </Label>

                    {subLable && (
                        <div className="text-xs text-muted-foreground">
                            ({subLable})
                        </div>
                    )}
                </div>

                <Textarea
                    ref={ref}
                    required={required}
                    {...props}
                />

                <InputError message={error} />
            </div>
        );
    },
);

FormTextarea.displayName = "FormTextarea";

export default FormTextarea;