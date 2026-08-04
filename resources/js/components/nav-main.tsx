import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import {
    BadgeIndianRupee,
    Building,
    Calculator,
    CalendarCheck,
    CalendarCog,
    ChevronRight,
    IdCardLanyard,
    IndianRupee,
    LayoutGrid,
    Scale,
    ScrollText,
    Settings,
    ShoppingBasket,
    ShoppingCart,
    Store,
    UserRoundArrowLeft,
    Users,
    UserStar,
} from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';

export function NavMain() {
    const { t } = useTranslation();

    const permissions = usePage<SharedData>().props.permissions;

    const checkPermission = (permission: string) =>
        permissions.length === 0 || permissions.includes(permission);

    const [openMenu, setOpenMenu] = useState<string | null>(null);

    const navItems = [
        {
            title: t('Platform'),
            items: [
                {
                    title: 'Overview',
                    url: 'dashboard.overview',
                    icon: LayoutGrid,
                    isActive: route().current('dashboard.overview'),
                    condition: checkPermission('overview'),
                },                
                {
                    title: 'Customers',
                    url: 'dashboard.customers.index',
                    icon: UserStar,
                    isActive: route().current('dashboard.customers.*'),
                    condition: checkPermission('customers'),
                },                
                {
                    title: 'Suppliers',
                    url: 'dashboard.suppliers.index',
                    icon: UserRoundArrowLeft,
                    isActive: route().current('dashboard.suppliers.*'),
                    condition: checkPermission('suppliers'),
                },                
                {
                    title: 'Measurement Units',
                    url: 'dashboard.measurement-units.index',
                    icon: Scale,
                    isActive: route().current('dashboard.measurement-units.*'),
                    condition: checkPermission('measurement-units'),
                },
                {
                    title: 'Products',
                    icon: ShoppingBasket,
                    isActive: route().current('dashboard.products.*'),
                    condition: checkPermission('products'),
                    children: [
                        {
                            title: 'Categories',
                            url: 'dashboard.products.categories',
                            isActive: route().current(
                                'dashboard.products.categories',
                            ),
                            condition: checkPermission('products'),
                        },
                        {
                            title: 'Products',
                            url: 'dashboard.products.index',
                            isActive: route().current(
                                'dashboard.products.index',
                            ),
                            condition: checkPermission('products'),
                        },
                    ],
                },
                {
                    title: 'Inventory',
                    url: 'dashboard.inventory.index',
                    icon: Store,
                    isActive: route().current('dashboard.inventory.*'),
                    condition: checkPermission('inventory'),
                },
                {
                    title: 'Purchases',
                    url: 'dashboard.purchases.index',
                    icon: ShoppingCart,
                    isActive: route().current('dashboard.purchases.*'),
                    condition: checkPermission('purchases'),
                },
                {
                    title: 'Sales',
                    url: 'dashboard.sales.index',
                    icon: BadgeIndianRupee,
                    isActive: route().current('dashboard.sales.*'),
                    condition: checkPermission('sales'),
                },
                {
                    title: 'Productions',
                    url: 'dashboard.productions.index',
                    icon: CalendarCog,
                    isActive: route().current('dashboard.productions.*'),
                    condition: checkPermission('productions'),
                },
                {
                    title: 'Expenses',
                    icon: IndianRupee,
                    isActive: route().current('dashboard.expenses.*'),
                    condition: checkPermission('expenses'),
                    children: [
                        {
                            title: 'Categories',
                            url: 'dashboard.expenses.categories',
                            isActive: route().current(
                                'dashboard.expenses.categories',
                            ),
                            condition: checkPermission('expenses'),
                        },
                        {
                            title: 'Expenses',
                            url: 'dashboard.expenses.index',
                            isActive: route().current(
                                'dashboard.expenses.index',
                            ),
                            condition: checkPermission('expenses'),
                        },
                    ],
                },
                {
                    title: 'Attendance',
                    url: 'dashboard.attendance.index',
                    icon: CalendarCheck,
                    isActive: route().current('dashboard.attendance.*'),
                    condition: checkPermission('attendance'),
                },                
                {
                    title: 'Reports',
                    url: 'dashboard.reports.index',
                    icon: ScrollText,
                    isActive: route().current('dashboard.reports.*'),
                    condition: checkPermission('reports'),
                },
                {
                    title: 'Calculator',
                    url: 'dashboard.calculator.index',
                    icon: Calculator,
                    isActive: route().current('dashboard.calculator.*'),
                    condition: checkPermission('calculator'),
                },
                {
                    title: 'Companies',
                    url: 'dashboard.companies.index',
                    icon: Building,
                    isActive: route().current('dashboard.companies.*'),
                    condition: checkPermission('companies'),
                },
                {
                    title: 'Employees',
                    url: 'dashboard.employees.index',
                    icon: IdCardLanyard,
                    isActive: route().current('dashboard.employees.*'),
                    condition: checkPermission('employees'),
                },
                {
                    title: 'Users',
                    url: 'dashboard.users.index',
                    icon: Users,
                    isActive: route().current('dashboard.users.*'),
                    condition: checkPermission('users'),
                },
                {
                    title: 'Settings',
                    url: 'dashboard.settings.profile',
                    icon: Settings,
                    isActive: route().current('dashboard.settings.*'),
                    condition: checkPermission('settings'),
                },
            ],
        },
    ];

    return (
        <>
            {navItems.map((group) => (
                <SidebarGroup key={group.title} className="px-2 py-0">
                    <SidebarGroupLabel>{group.title}</SidebarGroupLabel>

                    <SidebarMenu className="-mb-1">
                        {group.items
                            .filter((item) => item.condition)
                            .map((item) => {
                                if ('children' in item) {
                                    const active = item.children!.some(
                                        (child) => child.isActive,
                                    );

                                    return (
                                        <Collapsible
                                            key={item.title}
                                            defaultOpen={active}
                                            className="group/collapsible"
                                            open={openMenu === item.title}
                                            onOpenChange={(open) =>
                                                setOpenMenu(
                                                    open ? item.title : null,
                                                )
                                            }
                                        >
                                            <SidebarMenuItem>
                                                <CollapsibleTrigger asChild>
                                                    <SidebarMenuButton
                                                        isActive={active}
                                                    >
                                                        <item.icon />
                                                        <span>
                                                            {t(item.title)}
                                                        </span>
                                                        <ChevronRight className="ml-auto transition-transform group-data-[state=open]/collapsible:rotate-90" />
                                                    </SidebarMenuButton>
                                                </CollapsibleTrigger>

                                                <CollapsibleContent>
                                                    <SidebarMenuSub>
                                                        {item
                                                            .children!.filter(
                                                                (child) =>
                                                                    child.condition,
                                                            )
                                                            .map((child) => (
                                                                <SidebarMenuSubItem
                                                                    key={
                                                                        child.title
                                                                    }
                                                                >
                                                                    <SidebarMenuSubButton
                                                                        asChild
                                                                        isActive={
                                                                            child.isActive
                                                                        }
                                                                    >
                                                                        <Link
                                                                            href={route(
                                                                                child.url,
                                                                            )}
                                                                        >
                                                                            <span>
                                                                                {t(
                                                                                    child.title,
                                                                                )}
                                                                            </span>
                                                                        </Link>
                                                                    </SidebarMenuSubButton>
                                                                </SidebarMenuSubItem>
                                                            ))}
                                                    </SidebarMenuSub>
                                                </CollapsibleContent>
                                            </SidebarMenuItem>
                                        </Collapsible>
                                    );
                                }

                                return (
                                    <SidebarMenuItem key={item.title}>
                                        <SidebarMenuButton
                                            asChild
                                            isActive={item.isActive}
                                            tooltip={item.title}
                                        >
                                            <Link href={route(item.url)}>
                                                <item.icon />
                                                <span>{t(item.title)}</span>
                                            </Link>
                                        </SidebarMenuButton>
                                    </SidebarMenuItem>
                                );
                            })}
                    </SidebarMenu>
                </SidebarGroup>
            ))}
        </>
    );
}
