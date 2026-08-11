import { forwardRef, type ComponentProps } from "react";

import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import InputError from "@/components/input-error";

interface FormInputProps extends ComponentProps<typeof Input> {
    label: string;
    error?: string;
    required?: boolean;
    containerClassName?: string; //for className eg: grid grid-cols-2
}

const FormInput = forwardRef<HTMLInputElement, FormInputProps>(
    (
        {
            label,
            error,
            required = false,
            containerClassName = "",
            ...props
        },
        ref,
    ) => {
        return (
            <div className={`grid gap-2 ${containerClassName}`}>
                <Label htmlFor={props.id} required={required}>
                    {label}
                </Label>

                <Input ref={ref} {...props} />

                <InputError message={error} />
            </div>
        );
    },
);

FormInput.displayName = "FormInput";

export default FormInput;