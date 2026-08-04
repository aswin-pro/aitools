import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { formatQty } from '@/helpers/format-quantity';
import { Product, Production } from '@/types';
import { useTranslation } from 'react-i18next';

export default function ViewProductionInfo({
    production,
    all_products,
}: {
    production: Production | undefined;
    all_products: Product[];
}) {
    const { t } = useTranslation();

    const getProductName = (productId: string) => {
        return all_products.find((product) => product.product_id === productId)
            ?.product_name;
    };

    const getUnit = (productId: string) => {
        return all_products.find((product) => product.product_id === productId)
            ?.measurement_unit?.unit;
    };

    // outputs
    const outputs = production?.production_outputs ?? [];

    // inputs
    const inputs = production?.production_inputs ?? [];

    return (
        <div>
            {/* Production Items */}
            <div>
                <h5 className="mb-4 border-b pb-2 font-medium">
                    {t('Production Items')}
                </h5>

                <div className="overflow-hidden rounded-lg border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead className="w-12 min-w-12 border-r">
                                    {t('S.No')}
                                </TableHead>
                                <TableHead className="w-72 min-w-72 border-r">
                                    {t('Product')}
                                </TableHead>
                                <TableHead className="w-36 min-w-36">
                                    {t('Quantity')}
                                </TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            {outputs.length > 0 ? (
                                outputs.map((item, index) => (
                                    <TableRow key={index}>
                                        <TableCell className="border-r">
                                            {index + 1}
                                        </TableCell>
                                        <TableCell className="border-r">
                                            {getProductName(item.product_id)}
                                        </TableCell>
                                        <TableCell>
                                            {formatQty(item.quantity)} {getUnit(item.product_id)}
                                        </TableCell>
                                    </TableRow>
                                ))
                            ) : (
                                <TableRow>
                                    <TableCell
                                        colSpan={3}
                                        className="text-center"
                                    >
                                        {t('No data available.')}
                                    </TableCell>
                                </TableRow>
                            )}
                        </TableBody>
                    </Table>
                </div>
            </div>

            {/* Used Materials */}
            <div>
                <h5 className="my-4 border-b pb-2 font-medium">
                    {t('Used Materials')}
                </h5>

                <div className="overflow-hidden rounded-lg border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead className="w-12 min-w-12 border-r">
                                    {t('S.No')}
                                </TableHead>
                                <TableHead className="w-72 min-w-72 border-r">
                                    {t('Product')}
                                </TableHead>
                                <TableHead className="w-36 min-w-36">
                                    {t('Quantity')}
                                </TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            {inputs.length > 0 ? (
                                inputs.map((item, index) => (
                                    <TableRow key={index}>
                                        <TableCell className="border-r">
                                            {index + 1}
                                        </TableCell>
                                        <TableCell className="border-r">
                                            {getProductName(item.product_id)}
                                        </TableCell>
                                        <TableCell>
                                            {formatQty(item.quantity)} {getUnit(item.product_id)}
                                        </TableCell>
                                    </TableRow>
                                ))
                            ) : (
                                <TableRow>
                                    <TableCell
                                        colSpan={3}
                                        className="text-center"
                                    >
                                        {t('No data available.')}
                                    </TableCell>
                                </TableRow>
                            )}
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    );
}
