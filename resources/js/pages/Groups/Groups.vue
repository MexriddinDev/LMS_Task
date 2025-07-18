<template>
    <Head title="Guruhlar" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-4 text-gray-800 dark:text-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Guruhlar</h1>
                <Link :href="route('groups.create')">
                    <Button class="bg-white text-black border border-gray-300 hover:bg-gray-100 dark:bg-white dark:text-black dark:border-gray-600 dark:hover:bg-gray-200 px-5 py-2.5 rounded-lg transition-colors duration-200">
                        + Guruh qo‘shish
                    </Button>
                </Link>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <Card class="border-gray-200 shadow-sm dark:border-gray-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">Jami guruhlar</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <h2 class="text-2xl font-semibold text-black dark:text-white">{{ groups.length }}</h2>
                    </CardContent>
                </Card>
                <Card class="border-green-200 shadow-sm dark:border-green-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">Faol guruhlar</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <h2 class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ groups.length }}</h2>
                    </CardContent>
                </Card>
                <Card class="border-red-200 shadow-sm dark:border-red-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">Nofaol guruhlar</CardTitle>
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
                        <th class="p-3 text-left">Guruh nomi</th>
                        <th class="p-3 text-left">O'qituvchi</th>
                        <th class="p-3 text-left">Oylik to'lov</th>
                        <th class="p-3 text-left">Boshlanish sanasi</th>
                        <th class="p-3 text-left">Qo‘shildi</th>
                        <th class="p-3 text-left">Holat</th>
                        <th class="p-3 text-left">Harakatlar</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="group in groups" :key="group.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="p-3">
                            <div class="font-medium text-black dark:text-white">{{ group.name }}</div>
                        </td>
                        <td class="p-3 text-sm">
                            <div class="text-gray-500 dark:text-gray-400">
                                {{ group.teacher ? group.teacher.name : 'Noma\'lum' }}
                            </div>
                        </td>

                        <td class="p-3 text-sm">
                            <div class="text-gray-500 dark:text-gray-400">{{ group.monthly_fee }} UZS</div>
                        </td>
                        <td class="p-3 text-sm">
                            <div class="text-gray-500 dark:text-gray-400">{{ formatDate(group.start_date) }}</div>
                        </td>
                        <td class="p-3 text-sm">
                            <div class="text-gray-500 dark:text-gray-400">{{ formatDate(group.created_at) }}</div>
                        </td>
                        <td class="p-3">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                <svg class="h-4 w-4 mr-1 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15 4.293 10.879a1 1 0 111.414-1.414L8.414 12.172l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Faol
                            </span>
                        </td>
                        <td class="p-3 flex items-center gap-2">
                            <Link :href="route('groups.show', group.id)" title="Ko'rish">
                                <Button variant="ghost" size="icon" class="dark:text-gray-400 dark:hover:bg-gray-700">
                                    <i class="fas fa-eye text-gray-600 text-sm dark:text-gray-400"></i>
                                </Button>
                            </Link>

                            <Link :href="route('groups.edit', group.id)" title="Tahrirlash">
                                <Button variant="ghost" size="icon" class="dark:text-gray-400 dark:hover:bg-gray-700">
                                    <i class="fas fa-pen text-gray-600 text-sm dark:text-gray-400"></i>
                                </Button>
                            </Link>
                            <Button variant="ghost" size="icon" title="O‘chirish" class="dark:text-gray-400 dark:hover:bg-gray-700"
                                    @click="deleteConfirmation(group.id, group.name)">
                                <i class="fas fa-trash text-red-500 text-sm dark:text-red-400"></i>
                            </Button>
                        </td>
                    </tr>
                    <tr v-if="groups.length === 0">
                        <td colspan="7" class="p-4 text-center text-gray-500 dark:text-gray-400">
                            Hozircha guruhlar mavjud emas.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Button } from '@/components/ui/button'
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card'

const props = defineProps<{
    groups: {
        id: number
        name: string
        teacher_id: number
        monthly_fee: number
        start_date: string
        // Removed is_active since it's now hardcoded
        created_at: string
        teacher: {
            id: number
            name: string
        } | null
    }[]
}>()

const formatDate = (dateString: string) => {
    if (!dateString) return 'Noma\'lum sana';
    try {
        return new Date(dateString).toLocaleDateString('uz-UZ', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
        });
    } catch (e) {
        console.error("Invalid date string:", dateString, e);
        return 'Xato sana';
    }
};

// Function to handle delete confirmation using native browser dialog
const deleteConfirmation = (id: number, name: string) => {
    if (confirm(`Haqiqatan ham "${name}" guruhini o'chirmoqchimisiz? Bu amalni qaytarib bo'lmaydi!`)) {
        // If user confirms, proceed with deletion
        router.delete(route('groups.destroy', id), {
            onSuccess: () => {
                console.log('Guruh muvaffaqiyatli o‘chirildi!');
                // You might still want a simple success message, e.g., a toast, here
                alert('Guruh muvaffaqiyatli o‘chirildi!'); // For simplicity, using alert
            },
            onError: (errors) => {
                console.error('Guruhni o‘chirishda xatolik yuz berdi:', errors);
                alert('Guruhni o‘chirishda xatolik yuz berdi. Iltimos, qayta urinib ko‘ring.');
            }
        });
    } else {
        console.log('Guruhni o‘chirish bekor qilindi.');
    }
};


const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Guruhlar', href: '/groups' }
]
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
