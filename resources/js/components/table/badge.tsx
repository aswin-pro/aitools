import { Badge } from '@/components/ui/badge';

export const CustomBadge = (label?: string, color?: string) => <Badge className={color}>{label}</Badge>;
