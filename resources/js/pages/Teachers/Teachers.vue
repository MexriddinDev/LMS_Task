<template>
    <Head title="Xodimlar" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-4 text-gray-800 dark:text-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Foydalanuvchilar</h1>
                <Link :href="route('teachers.create')">
                    <Button class="bg-white text-black border border-gray-300 hover:bg-gray-100 dark:bg-white dark:text-black dark:border-gray-600 dark:hover:bg-gray-200 px-5 py-2.5 rounded-lg transition-colors duration-200">
                        + Xodim qo‘shish
                    </Button>
                </Link>
            </div>

            <div class="grid grid-cols-3 gap-4 ">
                <Card class="border-gray-200 shadow-sm dark:border-gray-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">Jami xodimlar</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <h2 class="text-2xl font-semibold text-black dark:text-white">{{ teachers.length }}</h2>
                    </CardContent>
                </Card>
                <Card class="border-green-200 shadow-sm dark:border-green-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">Faol xodimlar</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <h2 class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ teachers.length }}</h2>
                    </CardContent>
                </Card>
                <Card class="border-red-200 shadow-sm dark:border-red-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">Nofaol xodimlar</CardTitle>
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
                        <th class="p-3 text-left">Xodim</th>
                        <th class="p-3 text-left">Aloqa</th>
                        <th class="p-3 text-left">Holat</th>
                        <th class="p-3 text-left">Qo‘shildi</th>
                        <th class="p-3 text-left">Harakatlar</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="teacher in teachers" :key="teacher.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="p-3 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full border border-gray-400 flex items-center justify-center dark:border-gray-500">
                                <i class="fas fa-user text-gray-500 text-sm dark:text-gray-400"></i>
                            </div>
                            <div>
                                <div class="font-medium text-black dark:text-white">{{ teacher.name }}</div>
                                <div class="text-gray-500 text-sm dark:text-gray-400">Asosiy filial</div>
                            </div>
                        </td>
                        <td class="p-3 text-sm">
                            <div class="text-gray-500 dark:text-gray-400">{{ formatPhone(teacher.phone) }}</div>
                        </td>
                        <td class="p-3">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                <svg class="h-4 w-4 mr-1 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15 4.293 10.879a1 1 0 111.414-1.414L8.414 12.172l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Faol
                            </span>
                        </td>
                        <td class="p-3 text-sm">
                            <div class="text-gray-500 dark:text-gray-400">{{ formatDate(teacher.created_at) }}</div>
                        </td>
                        <td class="p-3">
                            <div class="flex items-center gap-2">
                                <!-- Ko‘rish -->
                                <Link :href="route('teachers.show', teacher.id)" title="Ko‘rish">
                                    <Button variant="outline" size="icon" class="dark:hover:bg-gray-700">
                                        <i class="fas fa-eye text-gray-600 text-sm dark:text-gray-300"></i>
                                    </Button>
                                </Link>

                                <!-- Tahrirlash -->
                                <Link :href="route('teachers.edit', teacher.id)" title="Tahrirlash">
                                    <Button variant="outline" size="icon" class="dark:hover:bg-gray-700">
                                        <i class="fas fa-pen text-gray-600 text-sm dark:text-gray-300"></i>
                                    </Button>
                                </Link>

                                <!-- O‘chirish -->
                                <Button variant="outline" size="icon" title="O‘chirish"
                                        class="dark:hover:bg-gray-700"
                                        @click="confirmDelete(teacher.id)">
                                    <i class="fas fa-trash text-red-500 text-sm dark:text-red-400"></i>
                                </Button>
                            </div>
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
import { Button } from '@/components/ui/button'
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card'

const props = defineProps<{
    teachers: {
        id: number
        name: string
        phone: string
        created_at: string
    }[]
}>()

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('uz-UZ', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    });
};

const confirmDelete = (id: number) => {
    if (confirm("Haqiqatan ham ushbu xodimni o'chirmoqchimisiz?")) {
        router.delete(route('teachers.destroy', id), {
            onSuccess: () => {
                console.log('Xodim muvaffaqiyatli o‘chirildi!');
            },
            onError: (errors) => {
                console.error('Xodimni o‘chirishda xatolik yuz berdi:', errors);
                alert('Xodimni o‘chirishda xatolik yuz berdi. Konsolni tekshiring.');
            }
        });


    }
};
const formatPhone = (phone: string) => {
    const digits = phone.replace(/\D/g, '');

    if (digits.length === 9) {
        return `${digits.slice(0, 2)}-${digits.slice(2, 5)}-${digits.slice(5, 7)}-${digits.slice(7)}`;
    }

    if (digits.length === 12 && digits.startsWith('998')) {
        const local = digits.slice(3);
        return `${local.slice(0, 2)}-${local.slice(2, 5)}-${local.slice(5, 7)}-${local.slice(7)}`;
    }

    return phone;
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Xodimlar', href: '/teachers' }
]
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
