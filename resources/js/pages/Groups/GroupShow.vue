<template>
    <Head :title="`${group.name} haqida`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-6 text-gray-800 dark:text-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <Link :href="route('groups.index')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </Link>
                    <div>
                        <h1 class="text-2xl font-bold">{{ group.name }}</h1>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('groups.edit', group.id)">
                        <Button variant="outline">
                            <i class="fas fa-pen mr-2"></i> Tahrirlash
                        </Button>
                    </Link>
                    <Button variant="destructive" @click="deleteGroupConfirmation(group.id, group.name)">
                        <i class="fas fa-trash mr-2"></i> O‘chirish
                    </Button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <Card class="lg:col-span-2 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <CardHeader class="p-0 pb-4 flex flex-row items-center justify-between">
                        <CardTitle class="text-lg font-semibold text-gray-800 dark:text-gray-100">Guruh ma'lumotlari</CardTitle>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                            <svg class="h-3 w-3 mr-1 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15 4.293 10.879a1 1 0 111.414-1.414L8.414 12.172l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Faol
                        </span>
                    </CardHeader>
                    <CardContent class="p-0 space-y-4">
                        <div class="space-y-2">
                            <h3 class="text-xl font-bold text-black dark:text-white">{{ group.name }}</h3>
                            <div class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <i class="fas fa-user-tie text-sm"></i> <span>O'qituvchi: {{ group.teacher ? group.teacher.name : 'Noma\'lum' }}</span>
                            </div>
                            <div class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <i class="fas fa-dollar-sign text-sm"></i> <span>Oylik to'lov: {{ group.monthly_fee }} UZS</span>
                            </div>
                            <div class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-sm"></i> <span>Boshlanish sanasi: {{ formatDate(group.start_date) }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <div class="lg:col-span-1 space-y-6">
                    <Card class="rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <CardHeader class="p-0 pb-4">
                            <CardTitle class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tezkor ma'lumot</CardTitle>
                        </CardHeader>
                        <CardContent class="p-0 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                    <i class="fas fa-id-card"></i> Guruh ID
                                </span>
                                <span class="text-black dark:text-white">{{ group.id }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                    <i class="fas fa-calendar-plus"></i> Qo'shilgan
                                </span>
                                <span class="text-black dark:text-white">{{ formatDate(group.created_at) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                    <i class="fas fa-history"></i> Oxirgi yangilanish
                                </span>
                                <span class="text-black dark:text-white">{{ formatDate(group.updated_at) }}</span>
                            </div>
                        </CardContent>
                    </Card>

                    <Card class="rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <CardHeader class="p-0 pb-4">
                            <CardTitle class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tezkor harakatlar</CardTitle>
                        </CardHeader>
                        <CardContent class="p-0 space-y-3">
                            <Link :href="route('groups.edit', group.id)" class="block">
                                <Button variant="ghost" class="w-full justify-start">
                                    <i class="fas fa-edit mr-2 text-gray-600 dark:text-gray-400"></i> Ma'lumotni tahrirlash
                                </Button>
                            </Link>
                            <Button variant="ghost" @click="deleteGroupConfirmation(group.id, group.name)"
                                    class="w-full justify-start text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-500">
                                <i class="fas fa-trash-alt mr-2"></i> Guruhni o'chirish
                            </Button>
                        </CardContent>
                    </Card>

                    <Card class="rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <CardHeader class="p-0 pb-4">
                            <CardTitle class="text-lg font-semibold text-gray-800 dark:text-gray-100">Qo'shimcha ma'lumot</CardTitle>
                        </CardHeader>
                        <CardContent class="p-0 space-y-3">
                            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                <i class="fas fa-users text-sm"></i>
                                <span>Guruh sig'imi: {{ group.capacity || 'Noma\'lum' }} o'quvchi</span>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <Card class="rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 mt-6">
                <CardHeader class="p-0 pb-4">
                    <CardTitle class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-user-graduate"></i> Guruhdagi o'quvchilar ({{ group.students.length }})
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-0">
                    <div v-if="group.students && group.students.length > 0" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ism Familya</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Telefon</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harakatlar</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="student in group.students" :key="student.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ student.full_name }}</div> </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ student.phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                            Faol
                                        </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <Link :href="route('students.show', student.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">
                                        <Button variant="ghost" size="icon" title="Ko'rish">
                                            <i class="fas fa-eye text-gray-600 text-sm dark:text-gray-400"></i>
                                        </Button>
                                    </Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="p-4 text-center text-gray-500 dark:text-gray-400">
                        Bu guruhda hozircha o'quvchilar mavjud emas.
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';

const props = defineProps<{
    group: {
        id: number;
        name: string;
        teacher_id: number;
        monthly_fee: number;
        start_date: string;
        capacity?: number;
        is_active?: boolean;
        created_at: string;
        updated_at: string;
        teacher: {
            id: number;
            name: string;
        } | null;
        students: { // Added students array for related students
            id: number;
            full_name: string; // Changed from 'name' to 'full_name'
            phone: string;
            is_active: boolean;
        }[];
    };
}>();

const formatDate = (dateString: string) => {
    if (!dateString) return 'Noma\'lum sana';
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) {
            return 'Xato sana';
        }
        return date.toLocaleDateString('uz-UZ', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
        });
    } catch (e) {
        console.error("Invalid date string:", dateString, e);
        return 'Xato sana';
    }
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Guruhlar', href: '/groups' },
    { title: props.group.name, href: `/groups/${props.group.id}` },
];

const deleteGroupConfirmation = (id: number, name: string) => {
    if (confirm(`Haqiqatan ham "${name}" guruhini o'chirmoqchimisiz? Bu amalni qaytarib bo'lmaydi!`)) {
        router.delete(route('groups.destroy', id), {
            onSuccess: () => {
                alert('Guruh muvaffaqiyatli o‘chirildi!');
                router.visit(route('groups.index'));
            },
            onError: (errors) => {
                console.error('Guruhni o‘chirishda xatolik yuz berdi:', errors);
                alert('Guruhni o‘chirishda xatolik yuz berdi. Iltimos, qayta urinib ko‘ring.');
            }
        });
    }
};
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
