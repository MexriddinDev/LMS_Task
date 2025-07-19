<template>
    <Head title="Tolovlar" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-4 text-gray-800 dark:text-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tolovlar</h1>
                <Link :href="route('payments.create')">
                    <Button class="bg-white dark:bg-gray-900 text-gray-700 dark:text-white border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 px-5 py-2.5 rounded-lg transition-colors duration-200">
                        + Tolov qo‘shish
                    </Button>
                </Link>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <Card class="border-gray-200 shadow-sm dark:border-gray-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">Jami to'lovlar</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <h2 class="text-2xl font-semibold text-black dark:text-white">{{ payments.length }}</h2>
                    </CardContent>
                </Card>
                <Card class="border-green-200 shadow-sm dark:border-green-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">Balansga o'tkazilgan</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <h2 class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ balancePaymentsComputed }}</h2>
                    </CardContent>
                </Card>
                <Card class="border-red-200 shadow-sm dark:border-red-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">Qarzdan yopilgan</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <h2 class="text-2xl font-semibold text-red-600 dark:text-red-400">{{ debtPaymentsComputed }}</h2>
                    </CardContent>
                </Card>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="p-3 text-left text-sm font-semibold">To'lov nomi</th>
                        <th class="p-3 text-left text-sm font-semibold">Turi</th>
                        <th class="p-3 text-left text-sm font-semibold">Tarbiyalanuvchi</th>
                        <th class="px-8 py-3 text-right text-sm font-semibold">Miqdor</th> <th class="px-8 py-3 text-left text-sm font-semibold">To'lov usuli</th> <th class="p-3 text-center text-sm font-semibold">Holati</th>
                        <th class="p-3 text-left text-sm font-semibold">Sana</th>
                        <th class="p-3 text-center text-sm font-semibold">Harakatlar</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr
                        v-for="payment in payments"
                        :key="payment.id"
                        class="hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-150"
                    >
                        <td class="p-3 text-sm font-medium text-left">{{ generatePaymentCode(payment.id) }}</td>
                        <td class="p-3 text-sm text-left">
                            <span :class="{
                                'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium': true,
                                'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300': payment.type === 'debt',
                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': payment.type === 'balance'
                            }">
                                <i :class="{
                                    'fas fa-money-bill-wave': payment.type === 'debt',
                                    'fas fa-wallet': payment.type === 'balance'
                                }"></i>
                                {{ payment.type === 'debt' ? 'Qarz to\'lovi' : 'Balansga' }}
                            </span>
                        </td>
                        <td class="p-3 text-sm text-left">
                            {{ payment.student?.full_name || 'Noma\'lum' }}
                        </td>
                        <td class="px-8 py-3 text-sm font-medium text-right">{{ formatCurrency(payment.amount) }}</td> <td class="px-8 py-3 text-sm text-gray-600 dark:text-gray-300 text-left">{{ payment.payment_method || 'Naqd' }}</td> <td class="p-3 text-sm text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                <i class="fas fa-check-circle text-green-400"></i>
                                Yakunlangan
                            </span>
                    </td>
                        <td class="p-3 text-sm text-gray-600 dark:text-gray-300 text-left">{{ formatDate(payment.date) }}</td>
                        <td class="p-3 text-sm text-center">
                            <div class="flex items-center justify-center gap-2">
                                <Link :href="route('payments.show', payment.id)" title="Ko‘rish">
                                    <Button variant="outline" size="icon" class="border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-eye text-gray-600 dark:text-gray-300 text-sm"></i>
                                    </Button>
                                </Link>
                                <Link :href="route('payments.edit', payment.id)" title="Tahrirlash">
                                    <Button variant="outline" size="icon" class="border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-pen text-gray-600 dark:text-gray-300 text-sm"></i>
                                    </Button>
                                </Link>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="payments.length === 0">
                        <td colspan="8" class="p-4 text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900">
                            Hozircha hech qanday to'lov yo'q.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { computed } from 'vue';

interface Student {
    id: number;
    full_name: string;
}

interface Payment {
    id: number;
    student_id: number;
    student: Student;
    amount: number;
    type: 'debt' | 'balance';
    date: string;
    note?: string;
    debt_id?: number;
    payment_method?: string;
}

const props = defineProps<{
    payments: Payment[];
}>();

const balancePaymentsComputed = computed(() => {
    return props.payments.filter(payment => payment.type === 'balance').length;
});

const debtPaymentsComputed = computed(() => {
    return props.payments.filter(payment => payment.type === 'debt').length;
});

const formatDate = (dateString: string) => {
    if (!dateString) return '';
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) {
            return 'Noma’lum sana';
        }
        return date.toLocaleDateString('uz-UZ', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
        });
    } catch (e) {
        return 'Noma’lum sana';
    }
};

const formatCurrency = (amount: number) => {
    if (typeof amount !== 'number') return 'N/A';
    return new Intl.NumberFormat('uz-UZ', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount) + ' UZS';
};

function generatePaymentCode(id: number) {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const datePart = `${yyyy}${mm}${dd}`;

    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let hash = '';
    for (let i = 0; i < 6; i++) {
        const index = (id * (i + 3)) % chars.length;
        hash += chars[index];
    }

    return `PAY-${datePart}-${hash}`;
}

const confirmDelete = (id: number) => {
    if (confirm("Haqiqatan ham ushbu to‘lovni o‘chirmoqchimisiz?")) {
        router.delete(route('payments.destroy', id), {
            onSuccess: () => {
                console.log('Tolov muvaffaqiyatli o‘chirildi! ✅');
                alert("Tolov muvaffaqiyatli o‘chirildi! ✅");
            },
            onError: (errors) => {
                alert("Xatolik yuz berdi. To‘lovni o‘chirish imkoni bo‘lmadi.");
            }
        });
    }
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Tolovlar', href: '/payments' }
];
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
