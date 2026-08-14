import { forwardRef, type ComponentProps } from "react";

import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import InputError from "@/components/input-error";

interface FormInputProps extends ComponentProps<typeof Input> {
    label: string;
    error?: string;
    required?: boolean;
    subLable?: string;
    containerClassName?: string; //for className eg: grid grid-cols-2
    multiline?: boolean;
    rows?: number;
}

const FormInput = forwardRef<HTMLInputElement, FormInputProps>(
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

                <Input ref={ref} {...props} />

                <InputError message={error} />
            </div>
        );
    },
);

FormInput.displayName = "FormInput";

export default FormInput;
