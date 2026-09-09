import {
    InputGroup,
    InputGroupAddon,
    InputGroupText,
    InputGroupTextarea,
} from '@/components/ui/input-group';
import { FieldType } from '@/types';
import { useState } from 'react';

export default function TextareaWithCounter({ field }: { field: FieldType }) {
    const maxLength = Number(field.props.maxLength ?? 0);
    const [count, setCount] = useState(0);

    return (
        <InputGroup>
            <InputGroupTextarea
                id={field.id}
                name={field.id}
                {...field.props}
                maxLength={maxLength || undefined}
                onChange={(e) => {
                    setCount(e.target.value.length);
                    field.props.onChange?.(e);
                }}
            />

            {maxLength > 0 && (
                <InputGroupAddon align="block-end">
                    <InputGroupText className="text-xs text-muted-foreground">
                        {count} / {maxLength} characters
                    </InputGroupText>
                </InputGroupAddon>
            )}
        </InputGroup>
    );
}
