// import * as LabelPrimitive from '@radix-ui/react-label';
// import { cva, type VariantProps } from 'class-variance-authority';
// import * as React from 'react';

// import { cn } from '@/lib/utils';

// const labelVariants = cva('text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70');

// const Label = React.forwardRef<
//     React.ElementRef<typeof LabelPrimitive.Root>,
//     React.ComponentPropsWithoutRef<typeof LabelPrimitive.Root> & VariantProps<typeof labelVariants>
// >(({ className, ...props }, ref) => <LabelPrimitive.Root ref={ref} className={cn(labelVariants(), className)} {...props} />);
// Label.displayName = LabelPrimitive.Root.displayName;

// export { Label };


import * as LabelPrimitive from '@radix-ui/react-label';
import { cva, type VariantProps } from 'class-variance-authority';
import * as React from 'react';

import { cn } from '@/lib/utils';

const labelVariants = cva(
    'relative inline-block text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70'
);

interface LabelProps
    extends React.ComponentPropsWithoutRef<typeof LabelPrimitive.Root>,
        VariantProps<typeof labelVariants> {
    required?: boolean;
}

const Label = React.forwardRef<
    React.ElementRef<typeof LabelPrimitive.Root>,
    LabelProps
>(({ className, required = false, children, ...props }, ref) => (
    <LabelPrimitive.Root
        ref={ref}
        className={cn(labelVariants(), className)}
        {...props}
    >
        {children}

        {required && (
            <span
                className="absolute ml-1 text-xl -top-2 text-destructive"
                aria-hidden="true"
            >
                *
            </span>
        )}
    </LabelPrimitive.Root>
));

Label.displayName = LabelPrimitive.Root.displayName;

export { Label };