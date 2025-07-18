<template>
    <Head title="Qarzlar" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-4 text-gray-800 dark:text-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Qarzlar</h1>
                <Link :href="route('debts.create')">
                    <Button class="bg-white text-gray-800 border border-gray-300 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-50 dark:border-gray-700 dark:hover:bg-gray-800 px-5 py-2.5 rounded-lg shadow-sm dark:shadow-lg transition-colors duration-200">
                        + Qarz qo‘shish
                    </Button>
                </Link>
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
                        <h2 class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ debts.length }}</h2>
                    </CardContent>
                </Card>
                <Card class="border-red-200 shadow-sm dark:border-red-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">To'lanmagan</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <h2 class="text-2xl font-semibold text-red-600 dark:text-red-400">{{ 0 }}</h2>
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
                        <th class="p-3 text-left">Muddat</th>
                        <th class="p-3 text-left">Amallar</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="debt in debts" :key="debt.id" class="hover:bg-gray-50 dark:hover:bg-gray-950 transition-colors duration-200">
                        <td class="p-3 text-gray-800 dark:text-gray-200">{{ generateDebtCode(debt.id) }}</td>
                        <td class="p-3 text-gray-800 dark:text-gray-200">{{ debt.student?.full_name || 'Nomaʼlum' }}</td>
                        <td class="p-3 text-gray-800 dark:text-gray-200">{{ debt.student?.group?.name || 'Guruhsiz' }}</td>
                        <td class="p-3 text-gray-800 dark:text-gray-200">{{ formatCurrency(debt.amount) }}</td>
                        <td class="p-3">
                            <div
                                v-if="debt.status === 'paid'"
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
                        <td class="p-3 text-gray-800 dark:text-gray-200">{{ formatDate(debt.due_date) }}</td>
                        <td class="p-3">
                            <div class="flex gap-2">
                                <Link :href="route('debts.show', debt.id)" title="Ko‘rish">
                                    <Button variant="outline" size="icon" class="border-gray-300 hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-900 shadow-sm dark:shadow-md">
                                        <i class="fas fa-eye text-gray-600 dark:text-gray-300 text-sm"></i>
                                    </Button>
                                </Link>
                                <Link :href="route('debts.edit', debt.id)" title="Tahrirlash">
                                    <Button variant="outline" size="icon" class="border-gray-300 hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-900 shadow-sm dark:shadow-md">
                                        <i class="fas fa-pen text-gray-600 dark:text-gray-300 text-sm"></i>
                                    </Button>
                                </Link>
                                <Button variant="outline" size="icon" title="O‘chirish" class="border-gray-300 hover:bg-red-50 dark:border-gray-700 dark:hover:bg-gray-900 shadow-sm dark:shadow-md" @click="confirmDelete(debt.id)">
                                    <i class="fas fa-trash text-red-500 dark:text-red-400 text-sm"></i>
                                </Button>
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
    group: Group;
}
interface Debt {
    id: number;
    student_id: number;
    student: Student;
    amount: number;
    due_date: string;
    status: 'paid' | 'unpaid';
}

const props = defineProps<{
    debts: Debt[];
    paidDebtsCount: number;
    unpaidDebtsCount: number;
}>();

function formatDate(dateString: string) {
    return new Date(dateString).toLocaleDateString('uz-UZ', {
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
