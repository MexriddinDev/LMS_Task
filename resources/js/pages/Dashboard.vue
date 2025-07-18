<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
// Shadcn Vue komponentlarini import qilish
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';

defineProps<{
    studentsCount: number;
    groupsCount: number;
    teachersCount: number;
    paymentsCount: number;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Xodimlar',
        href: '/teachers',
    },
    {
        title: 'Guruhlar',
        href: '/groups',
    },
    {
        title: 'Oquvchilar',
        href: '/students',
    }
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto text-gray-800 dark:text-gray-200">
            <div class="space-y-1">
                <h1 class="text-2xl font-bold">Boshqaruv paneli</h1>
                <p class="text-gray-600 dark:text-gray-400">Bolalar bog'chasi boshqaruvi</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Tarbiyalanuvchilar kartochkasi -->
                <Card class=" border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center text-center shadow-md">
                    <CardHeader class="pb-2 flex flex-col items-center">
                        <div class="rounded-lg bg-primary/10 p-1 sm:p-2 mb-2">
                            <!-- Ikonka: qorong'u rejimda oq rangda bo'lishi uchun dark:text-white qo'shildi -->
                            <svg class="lucide lucide-users-icon h-3 w-3 sm:h-5 sm:w-5 text-primary dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                        <CardDescription class="text-gray-500 dark:text-gray-400 text-sm">
                            Tarbiyalanuvchilar
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <CardTitle class="text-4xl font-bold text-gray-900 dark:text-white">
                            {{ studentsCount }}
                        </CardTitle>
                    </CardContent>
                </Card>

                <!-- Guruhlar kartochkasi -->
                <Card class=" border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center text-center shadow-md">
                    <CardHeader class="pb-2 flex flex-col items-center">
                        <div class="rounded-lg bg-primary/10 p-1 sm:p-2 mb-2">
                            <!-- Ikonka: qorong'u rejimda oq rangda bo'lishi uchun dark:text-white qo'shildi -->
                            <svg class="lucide lucide-building2-icon h-3 w-3 sm:h-5 sm:w-5 text-primary dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path><path d="M10 6h4"></path><path d="M10 10h4"></path><path d="M10 14h4"></path><path d="M10 18h4"></path></svg>
                        </div>
                        <CardDescription class="text-gray-500 dark:text-gray-400 text-sm">
                            Guruhlar
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <CardTitle class="text-4xl font-bold text-gray-900 dark:text-white">
                            {{ groupsCount }}
                        </CardTitle>
                    </CardContent>
                </Card>

                <!-- Xodimlar kartochkasi -->
                <Card class="border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center text-center shadow-md">
                    <CardHeader class="pb-2 flex flex-col items-center">
                        <div class="rounded-lg bg-primary/10 p-1 sm:p-2 mb-2">
                            <!-- Ikonka: qorong'u rejimda oq rangda bo'lishi uchun dark:text-white qo'shildi -->
                            <svg class="lucide lucide-user-check h-3 w-3 sm:h-5 sm:w-5 text-primary dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><polyline points="16 11 18 13 22 9"></polyline></svg>
                        </div>
                        <CardDescription class="text-gray-500 dark:text-gray-400 text-sm">
                            Xodimlar
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <CardTitle class="text-4xl font-bold text-gray-900 dark:text-white">
                            {{ teachersCount }}
                        </CardTitle>
                    </CardContent>
                </Card>

                <!-- Pul miqdori kartochkasi -->
                <Card class="border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center text-center shadow-md">
                    <CardHeader class="pb-2 flex flex-col items-center">
                        <div class="rounded-lg bg-primary/10 p-1 sm:p-2 mb-2">
                            <!-- Ikonka: qorong'u rejimda oq rangda bo'lishi uchun dark:text-white qo'shildi -->
                            <svg class="lucide lucide-dollar-sign h-3 w-3 sm:h-5 sm:w-5 text-primary dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <CardDescription class="text-gray-500 dark:text-gray-400 text-sm">
                            Pul miqdori
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <CardTitle class="text-3xl font-bold text-gray-800 dark:text-white">
                            {{ paymentsCount }}
                        </CardTitle>
                    </CardContent>
                </Card>
            </div>

            <!-- Oxirgi faoliyat kartochkasi (endi Shadcn Card) -->
            <!-- Gradient klasslari olib tashlandi, endi u standart Shadcn Card fonini ishlatadi -->
            <Card class="flex-1 border border-gray-200 dark:border-gray-700 shadow-md flex items-center justify-center min-h-[200px] transition-all duration-300 hover:shadow-lg">
                <CardContent class="text-center flex flex-col items-center">
                    <!-- Ikonka: qorong'u rejimda oq rangda bo'lishi uchun dark:text-white qo'shildi -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open-icon h-12 w-12 sm:h-16 sm:w-16 text-primary mb-4 dark:text-white">
                        <path d="M12 7v14"></path>
                        <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-xl font-medium mb-2">Hech qanday oxirgi faoliyat yo'q</p>
                    <p class="text-gray-500 dark:text-gray-400 text-base">Joriy foydalanuvchi bilan faoliyat bu yerda paydo bo'ladi</p>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Font Awesome endi kerak emas bo'lsa, buni olib tashlashingiz mumkin */
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
