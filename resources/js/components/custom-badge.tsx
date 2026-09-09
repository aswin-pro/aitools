import { Badge } from '@/components/ui/badge';

// Custom badge colors
const badgeColors = {
    success: "bg-green-500 dark:bg-green-800",
    danger: "bg-red-500 dark:bg-red-800",
    warning: "bg-orange-500 dark:bg-orange-800",
    info: "bg-blue-500 dark:bg-blue-800",
    default: "bg-gray-600 dark:bg-white",
} as const;

export function CustomBadge({
    type,
    children,
    className
}: {
    type: keyof typeof badgeColors;
    children: React.ReactNode;
    className?: string;
}) {
    return (
        <Badge
            className={`${className} ${badgeColors[type]} text-white`}
        >
            {children}
        </Badge>
    );
}
