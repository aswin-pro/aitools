import { FieldType } from '@/types';
import { Input } from '../ui/input';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
    InputGroupText,
} from '../ui/input-group';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '../ui/select';

type Props<T extends object> = {
    column: FieldType<T>;
    item: T;
    index: number;
    updateItem: (index: number, field: keyof T, value: string | number) => void;
};

export default function DynamicTableField<T extends object>({
    column,
    item,
    index,
    updateItem,
}: Props<T>) {
    const itemValue = item[column.id as keyof T];

    const inputValue =
        typeof column.value === 'function'
            ? column.value(item)
            : (column.value ??
              (itemValue as React.InputHTMLAttributes<HTMLInputElement>['value']));

    const addonContent =
        typeof column.inputGroup?.content === 'function'
            ? column.inputGroup.content(item)
            : column.inputGroup?.content;

    const max =
        typeof column.props.max === 'function'
            ? column.props.max(item)
            : column.props.max;

    switch (column.fieldType) {
        case 'select':
            return (
                <Select
                    name={`items[${index}][${column.id}]`}
                    value={String(itemValue ?? '')}
                    onValueChange={(value) =>
                        updateItem(index, column.id as keyof T, value)
                    }
                    required={column.props.required}
                >
                    <SelectTrigger>
                        <SelectValue placeholder={column.props.placeholder} />
                    </SelectTrigger>

                    <SelectContent>
                        {column.props.options?.map((option) => (
                            <SelectItem key={option.value} value={option.value}>
                                {option.label}
                            </SelectItem>
                        ))}
                    </SelectContent>
                </Select>
            );

        case 'input':
            return (
                <Input
                    className="no-spinner"
                    name={`items[${index}][${column.id}]`}
                    {...column.props}
                    max={max}
                    value={inputValue ?? ''}
                    onChange={(e) =>
                        updateItem(
                            index,
                            column.id as keyof T,
                            column.props.type === 'number'
                                ? Number(e.target.value)
                                : e.target.value,
                        )
                    }
                />
            );

        case 'input-group':
            return (
                <InputGroup>
                    <InputGroupInput
                        className="no-spinner"
                        name={`items[${index}][${column.id}]`}
                        {...column.props}
                        max={max}
                        value={inputValue ?? ''}
                        onChange={(e) =>
                            updateItem(
                                index,
                                column.id as keyof T,
                                column.props.type === 'number'
                                    ? Number(e.target.value)
                                    : e.target.value,
                            )
                        }
                    />

                    <InputGroupAddon
                        align={column.inputGroup?.align ?? 'inline-end'}
                    >
                        <InputGroupText>{addonContent}</InputGroupText>
                    </InputGroupAddon>
                </InputGroup>
            );

        default:
            return null;
    }
}
