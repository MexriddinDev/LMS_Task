<template>
    <Head title="Qarzlar" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-4 text-gray-800 dark:text-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Qarzlar</h1>
<!--                <Link :href="route('debts.create')">-->
<!--                    <Button class="bg-white text-gray-800 border border-gray-300 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-50 dark:border-gray-700 dark:hover:bg-gray-800 px-5 py-2.5 rounded-lg shadow-sm dark:shadow-lg transition-colors duration-200">-->
<!--                        + Qarz qo‘shish-->
<!--                    </Button>-->
<!--                </Link>-->
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <Card class="border-gray-200 shadow-sm dark:border-gray-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">Jami qarzlar</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <h2 class="text-2xl font-semibold text-black dark:text-white">{{ debts.length }}</h2>
                    </CardContent>
                </Card>
                <Card class="border-green-200 shadow-sm dark:border-green-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">To'langan</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <h2 class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ paidDebtsCount }}</h2>
                    </CardContent>
                </Card>
                <Card class="border-red-200 shadow-sm dark:border-red-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">To'lanmagan</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <h2 class="text-2xl font-semibold text-red-600 dark:text-red-400">{{ unpaidDebtsCount }}</h2>
                    </CardContent>
                </Card>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="p-3 text-left">Kod</th>
                        <th class="p-3 text-left">Talaba</th>
                        <th class="p-3 text-left">Guruh</th>
                        <th class="p-3 text-left">Miqdor</th>
                        <th class="p-3 text-left">Holat</th>
                        <th class="p-3 text-left">Yaratilgan sana</th> </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="debt in debts" :key="debt.id" class="hover:bg-gray-50 dark:hover:bg-gray-950 transition-colors duration-200">
                        <td class="p-3 text-gray-800 dark:text-gray-200">{{ generateDebtCode(debt.id) }}</td>
                        <td class="p-3 text-gray-800 dark:text-gray-200">{{ debt.student?.full_name || 'Nomaʼlum' }}</td>
                        <td class="p-3 text-gray-800 dark:text-gray-200">{{ debt.group?.name || 'Guruhsiz' }}</td>
                        <td class="p-3 text-gray-800 dark:text-gray-200">{{ formatCurrency(debt.amount) }}</td>
                        <td class="p-3">
                            <div
                                v-if="debt.is_paid"
                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-green-100 text-green-700 text-xs dark:bg-green-900 dark:text-green-300"
                            >
                                <i class="fas fa-check-circle text-green-500 dark:text-green-400"></i> To‘langan
                            </div>
                            <div
                                v-else
                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-yellow-100 text-yellow-700 text-xs dark:bg-yellow-900 dark:text-yellow-300"
                            >
                                <i class="fas fa-hourglass-half text-yellow-500 dark:text-yellow-400"></i> To‘lanmagan
                            </div>
                        </td>
                        <td class="p-3 text-gray-800 dark:text-gray-200">{{ formatDate(debt.created_at) }}</td> <td class="p-3">
                        <div class="flex gap-2">
                        </div>
                    </td>
                    </tr>
                    <tr v-if="debts.length === 0">
                        <td colspan="7" class="p-4 text-center text-gray-500 bg-gray-100 dark:bg-gray-950 dark:text-gray-400 transition-colors duration-200">Hech qanday qarz mavjud emas.</td>
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

interface Group {
    id: number;
    name: string;
}
interface Student {
    id: number;
    full_name: string;
}
interface Debt {
    id: number;
    student_id: number;
    student: Student;
    amount: number;
    due_date: string | null; // Keep due_date for type consistency if it exists in data, though not displayed
    created_at: string; // Add created_at property
    status: 'paid' | 'unpaid';
    is_paid: boolean;
    group: Group;
}

const props = defineProps<{
    debts: Debt[];
    paidDebtsCount: number;
    unpaidDebtsCount: number;
}>();

function formatDate(dateString: string | null): string {
    if (!dateString) {
        return ''; // Return empty string if dateString is null or empty
    }
    const date = new Date(dateString);
    if (isNaN(date.getTime())) {
        // Check if the date is actually invalid (e.g., "Invalid Date" object)
        return 'Yaroqsiz sana'; // Or a more user-friendly message
    }
    return date.toLocaleDateString('uz-UZ', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
}

function formatCurrency(amount: number) {
    return new Intl.NumberFormat('uz-UZ', {
        style: 'decimal',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount) + ' so‘m';
}

function generateDebtCode(id: number) {
    const today = new Date();
    const date = `${today.getFullYear()}${String(today.getMonth() + 1).padStart(2, '0')}${String(today.getDate()).padStart(2, '0')}`;
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let hash = '';
    for (let i = 0; i < 6; i++) {
        const index = (id * (i + 2)) % chars.length;
        hash += chars[index];
    }
    return `DEBT-${date}-${hash}`;
}

function confirmDelete(id: number) {
    if (confirm("Ushbu qarzni o‘chirmoqchimisiz?")) {
        router.delete(route('debts.destroy', id));
    }
}

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Qarzlar', href: '/debts' },
];
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
