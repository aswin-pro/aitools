import { assetUrl } from '@/helpers/asset-url';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { ItemsTable } from './items-table';
import { useAppearance } from '@/hooks/use-appearance';
import { Transaction } from '@/types/user';

export function Invoice({
    t,
    transaction,
}: {
    t: (key: string) => string;
    transaction: Transaction;
}) {
    // check is dark
    const { isDark } = useAppearance();
    const { logo_dark, logo_light } = usePage<SharedData>().props;

    // Select logo based on theme
    const logo = isDark ? logo_dark : logo_light;

    return (
        <div className="space-y-8 p-6">
            {/* Header Section */}
            <div className="grid grid-cols-2 gap-4">
                {/* Company */}
                <div className="space-y-1">
                    {/* Logo */}
                    <img
                        src={assetUrl(logo)}
                        width={150}
                        className="mb-2 object-cover"
                        alt="Company Logo"
                    />

                    {/* From name */}
                    <div className="text-lg font-medium">
                        {transaction.billing_details.from_billing_name}
                    </div>

                    {/* Address */}
                    <div className="text-sm">
                        {transaction.billing_details.from_billing_address},{' '}
                        {transaction.billing_details.from_billing_city},{' '}
                        {transaction.billing_details.from_billing_state}{' '}
                        {transaction.billing_details.from_billing_country}
                    </div>

                    {/* Email */}
                    <div className="text-sm">
                        <strong>{t('Email')}: </strong>
                        {transaction.billing_details.from_billing_email}
                    </div>

                    {/* Phone */}
                    {transaction.billing_details.from_billing_phone && (
                        <div className="text-sm">
                            <strong>{t('Phone')}: </strong>
                            {transaction.billing_details.from_billing_phone}
                        </div>
                    )}

                    {/* Tax Number */}
                    {transaction.billing_details.from_vat_number && (
                        <div className="text-sm">
                            <strong>{t('Tax Number')}: </strong>
                            {transaction.billing_details.from_vat_number}
                        </div>
                    )}
                </div>

                {/* Invoice Details */}
                <div className="text-end">
                    <h2 className="text-2xl font-bold">{t('Invoice')}</h2>

                    <h4 className="mt-2 text-base">
                        #{transaction.invoice_prefix}
                        {transaction.invoice_number}
                    </h4>
                </div>
            </div>

            {/* Billing Information */}
            <div className="grid grid-cols-2 gap-4">
                {/* Bill To */}
                <div className="space-y-1">
                    {/* Label */}
                    <h4 className="mb-1 font-medium text-muted-foreground">
                        {t('Bill To')}
                    </h4>

                    {/* Name */}
                    <div className="text-lg font-medium">
                        {transaction.billing_details.to_billing_name}
                    </div>

                    {/* Address */}
                    <div className="text-sm">
                        {transaction.billing_details.to_billing_address},{' '}
                        {transaction.billing_details.to_billing_city},{' '}
                        {transaction.billing_details.to_billing_state}{' '}
                        {transaction.billing_details.to_billing_country}
                    </div>

                    {/* Email */}
                    <div className="text-sm">
                        <strong>{t('Email')}: </strong>
                        {transaction.billing_details.to_billing_email}
                    </div>

                    {/* Phone */}
                    {transaction.billing_details.to_billing_phone && (
                        <div className="text-sm">
                            <strong>{t('Phone')}: </strong>
                            {transaction.billing_details.to_billing_phone}
                        </div>
                    )}

                    {/* Tax Number */}
                    {transaction.billing_details.to_vat_number && (
                        <div className="text-sm">
                            <strong>{t('Tax Number')}: </strong>
                            {transaction.billing_details.to_vat_number}
                        </div>
                    )}
                </div>

                {/* Invoice Meta */}
                <div className="text-end">
                    {/* Date */}
                    <p className="text-sm">
                        <strong>{t('Date')}: </strong>
                        {new Date(
                            transaction.transaction_date,
                        ).toLocaleDateString('en-US', {
                            month: 'short',
                            day: '2-digit',
                            year: 'numeric',
                        })}
                    </p>

                    {/* Balance Due */}
                    <h5 className="text-sm font-medium">
                        <strong>{t('Balance Due')}: </strong>
                        {transaction.billing_details.invoice_amount === 0
                            ? '0'
                            : '0'}
                    </h5>
                </div>
            </div>

            {/* Items */}
            <ItemsTable t={t} transaction={transaction} />

            {/* Notes */}
            <div>
                <strong>{t('Notes')}</strong>

                <p className="mt-1 text-sm text-muted-foreground">
                    {t('Payment from')} {transaction.payment_gateway_name}
                    <br />
                    {t('Transaction ID')}: {transaction.transaction_id || '-'}
                </p>
            </div>

            {/* Footer Message */}
            <p className="text-center text-sm text-muted-foreground">
                {t('Thank you for your business!')}
            </p>
        </div>
    );
}
