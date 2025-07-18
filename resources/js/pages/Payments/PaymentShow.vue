<template>
    <Head :title="`To'lov tafsilotlari: ${payment.id || 'Noma\'lum'}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-6 text-gray-800 dark:text-gray-200 max-w-4xl mx-auto">
            <div class="flex items-center justify-between gap-4">
                <Link :href="route('payments.index')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                    <i class="fas fa-arrow-left text-xl"></i>
                </Link>
                <h1 class="text-2xl font-bold">To'lov tafsilotlari: {{ payment.id || 'Noma\'lum' }}</h1>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                        Bajarilgan
                    </span>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                        Obuna to'lovi
                    </span>
                    <Link :href="route('payments.edit', payment.id)" class="bg-white text-black px-4 py-2 rounded-lg hover:bg-gray-100 dark:bg-white dark:text-black dark:hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>Tahrirlash
                    </Link>
                </div>
            </div>

            <p class="text-gray-600 dark:text-gray-400">To'lov va obuna ma'lumotlari</p>

            <div class="p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">To'lov ma'lumotlari</h2>
                <p class="text-gray-600 dark:text-gray-400">To'lov tafsilotlari va tranzaksiya ma'lumotlari</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ma'lumotnoma raqami</p>
                        <p class="text-gray-800 dark:text-gray-100">{{ payment.id ? `PAY-${payment.id}` : 'Kiritilmagan' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Miqdori</p>
                        <p class="text-gray-800 dark:text-gray-100">{{ payment.amount ? `${payment.amount} UZS` : '0 UZS' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">To'lov usuli</p>
                        <p class="text-gray-800 dark:text-gray-100">{{ payment.type || 'Naqd pul' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Yaratilgan sana</p>
                        <p class="text-gray-800 dark:text-gray-100">{{ formatDate(payment.created_at) || 'Noma\'lum' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">To'langan sana</p>
                        <p class="text-gray-800 dark:text-gray-100">{{ formatDate(payment.date) || 'To\'lanmagan' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jarayon qilgan</p>
                        <p class="text-gray-800 dark:text-gray-100">{{ payment.processed_by || 'Admin' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Oxirgi yangilanish</p>
                        <p class="text-gray-800 dark:text-gray-100">{{ formatDate(payment.updated_at) || 'Noma\'lum' }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Obuna ma'lumotlari</h2>
                <p class="text-gray-600 dark:text-gray-400">Tegishli obuna haqida ma'lumotlar</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">O'quvchi</p>
                        <p class="text-gray-800 dark:text-gray-100">
                            <Link :href="route('students.show', payment.student?.id || 1)" class="text-blue-500 hover:underline">
                                {{ payment.student?.name || 'Noma\'lum o\'quvchi' }}
                            </Link>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Reja</p>
                        <p class="text-gray-800 dark:text-gray-100">Premium Plan</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Obuna davri</p>
                        <p class="text-gray-800 dark:text-gray-100">Jul 1, 2024 - Jul 31, 2024</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Obuna holati</p>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                            Faol
                        </span>
                    </div>
                </div>
                <div class="mt-4">
                    <Link :href="route('subscriptions.show', 1)" class="text-blue-500 hover:underline text-sm font-medium">
                        To'liq obuna tafsilotlarini ko'rish <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

interface Student {
    id: number;
    name: string;
    // Add other student properties if needed
}

interface Payment {
    id?: number;
    student_id?: number;
    student?: Student; // Add student relationship
    amount?: number;
    date?: string; // This will be the 'paid_at' equivalent from your Payment model
    note?: string;
    type?: string; // This will be the 'payment_method' equivalent from your Payment model
    debt_id?: number;
    created_at?: string;
    updated_at?: string;
    // processed_by is not in the model, so it will be hardcoded or passed as an extra prop
    processed_by?: string;
}

const props = defineProps<{
    payment: Payment;
}>();

const formatDate = (dateString: string | null | undefined): string => {
    if (!dateString) return '';
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) {
            return '';
        }
        return date.toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    } catch (e) {
        console.error("Error formatting date:", e);
        return '';
    }
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'To\'lovlar', href: route('payments.index') },
    { title: `Tafsilotlar: ${props.payment?.id || 'Noma\'lum'}`, href: props.payment?.id ? route('payments.show', props.payment.id) : '#' }
];
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
