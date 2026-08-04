import { format } from 'date-fns';

import FormField from '@/components/form/form-field';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
    InputGroupText,
} from '@/components/ui/input-group';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { FieldType } from '@/types';

type Props = {
    fields: FieldType[];
    errors: Record<string, string>;
    dates?: Record<string, Date | undefined>;
    setDates?: React.Dispatch<
        React.SetStateAction<Record<string, Date | undefined>>
    >;
};

export default function DynamicRenderFields({
    fields,
    errors,
    dates,
    setDates,
}: Props) {
    const addonContent = (field: FieldType) => {
        const content = field.inputGroup?.content;

        return typeof content === 'function' ? null : content;
    };

    return (
        <>
            {fields
                .filter((field) => field.show !== false)
                .map((field) => (
                    <FormField
                        key={field.id}
                        id={field.id}
                        label={field.label ?? ''}
                        error={errors[field.id]}
                        required={field.props.required}
                    >
                        {(() => {
                            switch (field.fieldType) {
                                case 'input':
                                    return (
                                        // @ts-expect-error -- field.props.max may be a DynamicValue; handled at runtime
                                        <Input
                                            id={field.id}
                                            name={field.id}
                                            className="no-spinner"
                                            {...field.props}
                                        />
                                    );

                                case 'input-group':
                                    return (                                        
                                        <InputGroup>
                                            {/* @ts-expect-error -- field.props.max may be a DynamicValue; handled at runtime */}
                                            <InputGroupInput
                                                id={field.id}
                                                name={field.id}
                                                className="no-spinner"
                                                {...field.props}
                                            />
                                            <InputGroupAddon
                                                align={
                                                    field.inputGroup?.align ??
                                                    'inline-start'
                                                }
                                            >
                                                <InputGroupText>
                                                    {addonContent(field)}
                                                </InputGroupText>
                                            </InputGroupAddon>
                                        </InputGroup>
                                    );

                                case 'text-area':
                                    return (
                                        <Textarea
                                            id={field.id}
                                            name={field.id}
                                            placeholder={
                                                field.props.placeholder
                                            }
                                            defaultValue={field.props.defaultValue?.toString()}
                                        />
                                    );

                                case 'select':
                                    return (
                                        <Select
                                            name={field.id}
                                            defaultValue={field.props.defaultValue?.toString()}
                                            required={field.props.required}
                                            onValueChange={
                                                field.props.onValueChange
                                            }
                                        >
                                            <SelectTrigger id={field.id}>
                                                <SelectValue
                                                    placeholder={
                                                        field.props.placeholder
                                                    }
                                                />
                                            </SelectTrigger>

                                            <SelectContent>
                                                {field.props.options?.map(
                                                    (option) => (
                                                        <SelectItem
                                                            key={option.value}
                                                            value={option.value}
                                                        >
                                                            {option.label}
                                                        </SelectItem>
                                                    ),
                                                )}
                                            </SelectContent>
                                        </Select>
                                    );

                                case 'date-picker':
                                    return (
                                        <>
                                            <Popover>
                                                <PopoverTrigger asChild>
                                                    <Button
                                                        variant="outline"
                                                        className="w-full justify-start"
                                                    >
                                                        {dates?.[field.id]
                                                            ? format(
                                                                  dates[
                                                                      field.id
                                                                  ]!,
                                                                  'PPP',
                                                              )
                                                            : field.props
                                                                  .placeholder}
                                                    </Button>
                                                </PopoverTrigger>

                                                <PopoverContent
                                                    className="w-auto p-0"
                                                    align="start"
                                                >
                                                    <Calendar
                                                        mode="single"
                                                        captionLayout="dropdown"
                                                        selected={
                                                            dates?.[field.id]
                                                        }
                                                        onSelect={(date) =>
                                                            setDates?.(
                                                                (prev) => ({
                                                                    ...prev,
                                                                    [field.id]:
                                                                        date,
                                                                }),
                                                            )
                                                        }
                                                    />
                                                </PopoverContent>
                                            </Popover>

                                            <input
                                                type="hidden"
                                                name={field.id}
                                                value={
                                                    dates?.[field.id]
                                                        ? format(
                                                              dates[field.id]!,
                                                              'yyyy-MM-dd',
                                                          )
                                                        : ''
                                                }
                                            />
                                        </>
                                    );

                                case 'checkbox':
                                    return (
                                        <Checkbox
                                            id={field.id}
                                            name={field.id}
                                        />
                                    );

                                default:
                                    return null;
                            }
                        })()}
                    </FormField>
                ))}
        </>
    );
}
