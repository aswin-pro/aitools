import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

interface FormFileProps {
    id: string;
    name: string;
    label: string;
    required?: boolean;
    error?: string;
    disabled?: boolean;
    onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
}

export default function FormFile({
    id,
    name,
    label,
    required = false,
    error,
    disabled = false,
    onChange,
}: FormFileProps) {
    return (
        <div className="grid gap-2">
            <Label htmlFor={id} required={required}>
                {label}
            </Label>

            <Input
                id={id}
                name={name}
                type="file"
                accept="image/jpeg,image/png,image/webp"
                disabled={disabled}
                onChange={onChange}
            />

            {error && (
                <p className="text-sm text-destructive">
                    {error}
                </p>
            )}
        </div>
    );
}