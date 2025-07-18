<template>
    <Head :title="`${teacher.name} haqida`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-6 text-gray-800 dark:text-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <Link :href="route('teachers.index')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </Link>
                    <div>
                        <h1 class="text-2xl font-bold">{{ teacher.name }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Xodim tafsilotlari va ma'lumotlari</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('teachers.edit', teacher.id)">
                        <Button variant="outline">
                            <i class="fas fa-pen mr-2"></i> Tahrirlash
                        </Button>
                    </Link>
                    <Button variant="destructive" @click="deleteTeacher(teacher.id, teacher.name)">
                        <i class="fas fa-trash mr-2"></i> O‘chirish
                    </Button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <Card class="lg:col-span-2 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <CardHeader class="p-0 pb-4">
                        <CardTitle class="text-lg font-semibold text-gray-800 dark:text-gray-100">Abdurashid</CardTitle>
                    </CardHeader>
                    <CardContent class="p-0">
                        <div class="flex items-center gap-4">
                            <img :src="teacher.profile_picture_url || '/images/default-avatar.png'" alt="Profil rasmi"
                                 class="w-20 h-20 rounded-full object-cover shadow-md" />
                            <div>
                                <h3 class="text-xl font-bold text-black dark:text-white inline-flex items-center gap-2">
                                    {{ teacher.name }}
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                        <svg class="h-3 w-3 mr-1 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15 4.293 10.879a1 1 0 111.414-1.414L8.414 12.172l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Faol
                                    </span>
                                </h3>
                                <div class="text-gray-600 dark:text-gray-400 flex items-center gap-2 mt-1">
                                    <i class="fas fa-at text-sm"></i> <span>example@mail.com</span>
                                </div>
                                <div class="text-gray-600 dark:text-gray-400 flex items-center gap-2 mt-1">
                                    <i class="fas fa-mobile-alt text-sm"></i> <span>{{ teacher.phone }}</span>
                                </div>
                                <div class="text-gray-600 dark:text-gray-400 flex items-center gap-2 mt-1">
                                    <i class="fas fa-building text-sm"></i> <span>Markaziy filial</span>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card class="lg:col-span-1 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <CardHeader class="p-0 pb-4">
                        <CardTitle class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tezkor ma'lumot</CardTitle>
                    </CardHeader>
                    <CardContent class="p-0 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <i class="fas fa-id-card"></i> Xodim ID
                            </span>
                            <span class="text-black dark:text-white">{{ teacher.id }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <i class="fas fa-calendar-alt"></i> Qo'shilgan
                            </span>
                            <span class="text-black dark:text-white">{{ formatDate(teacher.created_at) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <i class="fas fa-history"></i> Oxirgi yangilanish
                            </span>
                            <span class="text-black dark:text-white">{{ formatDate(teacher.updated_at) }}</span>
                        </div>
                    </CardContent>
                </Card>

                <div class="lg:col-span-3 grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

                    <Card class="lg:col-span-2 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <CardHeader class="p-0 pb-4">
                            <CardTitle class="text-lg font-semibold text-gray-800 dark:text-gray-100">Hisob ma'lumotlari</CardTitle>
                        </CardHeader>
                        <CardContent class="p-0 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                                    <i class="fas fa-envelope"></i> Elektron pochta
                                </label>
                                <p class="text-black dark:text-white">example@mail.com</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                                    <i class="fas fa-phone-alt"></i> Telefon
                                </label>
                                <p class="text-black dark:text-white">{{ teacher.phone }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt"></i> Filial
                                </label>
                                <p class="text-black dark:text-white">Markaziy filial</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                                    <i class="fas fa-info-circle"></i> Holat
                                </label>
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                    <svg class="h-4 w-4 mr-1 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15 4.293 10.879a1 1 0 111.414-1.414L8.414 12.172l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Faol
                                </span>
                            </div>
                        </CardContent>
                    </Card>

                    <Card class="lg:col-span-1 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <CardHeader class="p-0 pb-4">
                            <CardTitle class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tezkor harakatlar</CardTitle>
                        </CardHeader>
                        <CardContent class="p-0 space-y-3">
                            <Link :href="route('teachers.edit', teacher.id)" class="block">
                                <Button variant="ghost" class="w-full justify-start">
                                    <i class="fas fa-edit mr-2 text-gray-600 dark:text-gray-400"></i> Ma'lumotni tahrirlash
                                </Button>
                            </Link>
                            <Button variant="ghost" @click="deleteTeacher(teacher.id, teacher.name)" class="w-full justify-start text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-500">
                                <i class="fas fa-trash-alt mr-2"></i> Xodimni o'chirish
                            </Button>
                        </CardContent>
                    </Card>
                </div>

            </div>

            <div class="rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 text-gray-600 dark:text-gray-400">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2 flex items-center gap-2">
                    <i class="fas fa-user-shield"></i> Xodimni boshqaruvchi
                </h3>
                <p>Xodim kirishini va ruxsatlarini boshqarishingiz mumkin.</p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
// Shadcn Vue components
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';

const props = defineProps<{
    teacher: {
        id: number;
        name: string;
        phone: string;
        created_at: string;
        updated_at: string;
        profile_picture_url?: string;
    };
}>();

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

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Xodimlar', href: '/teachers' },
    { title: props.teacher.name, href: `/teachers/${props.teacher.id}` },
];

const deleteTeacher = (id: number, name: string) => {
    if (confirm(`Siz rostdan ham "${name}" xodimini o'chirishni xohlaysizmi? Bu amalni qaytarib bo'lmaydi!`)) {
        useForm({}).delete(route('teachers.destroy', id), {
            onSuccess: () => {
                window.location.href = route('teachers.index');
            },
            onError: (errors) => {
                console.error('Xodimni o‘chirishda xatolik yuz berdi:', errors);
                alert('Xodimni o‘chirishda xatolik yuz berdi. Iltimos, qayta urinib ko‘ring.');
            }
        });
    }
};
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
