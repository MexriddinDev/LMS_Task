<template>
    <Head :title="student.full_name || 'Tarbiyalanuvchi ma\'lumotlari'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-6 bg-white text-gray-800 dark:bg-black dark:text-gray-200 min-h-screen">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <Link :href="route('students.index')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </Link>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ student.full_name }}</h1>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                    Guruh: <span class="font-medium text-gray-900 dark:text-white">Katta guruh</span>
                </div>
            </div>

            <div class="flex justify-end gap-3 -mt-2 mb-4">
                <Link :href="route('students.edit', student.id)" class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 text-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors duration-200 text-sm font-medium">
                    <i class="fas fa-edit mr-2"></i> Tarbiyalanuvchini tahrirlash
                </Link>
                <button @click="confirmDelete(student.id)" class="inline-flex items-center justify-center bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors duration-200 text-sm font-medium">
                    <i class="fas fa-trash-alt mr-2"></i> O‘chirish
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm bg-white dark:bg-black">
                        <div class="pb-4 border-b border-gray-200 dark:border-gray-700 mb-4">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tarbiyalanuvchi ma'lumotlari</h2>
                        </div>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 rounded-full bg-white dark:bg-black flex items-center justify-center text-black dark:text-white text-3xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ student.full_name }}</h3>
                                <div class="text-gray-600 dark:text-gray-400 flex items-center gap-2 mt-1 text-sm">
                                    <i class="fas fa-phone text-xs"></i> <span>{{ student.phone || 'Berilmagan' }}</span>
                                </div>
                                <div class="text-gray-600 dark:text-gray-400 flex items-center gap-2 mt-1 text-sm">
                                    <i class="fas fa-coins text-xs"></i> <span>Balans: {{ formatCurrency(student.balance) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-gray-700 dark:text-gray-300">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tug'ilgan sana</label>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ student.birth_date ? formatDate(student.birth_date) : 'Berilmagan' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tug'ilgan guvohnomasi raqami</label>
                                <p class="font-semibold text-gray-900 dark:text-white">Berilmagan</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tayinlangan guruh</label>
                                <p class="font-semibold text-gray-900 dark:text-white">Katta guruh</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    <div class="p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4 bg-white dark:bg-black">
                        <div class="pb-4 border-b border-gray-200 dark:border-gray-700 mb-4">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tezkor ma'lumot</h2>
                        </div>
                        <div class="space-y-3 text-gray-700 dark:text-gray-300 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-id-card text-base"></i> Tarbiyalanuvchi ID
                                </span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ student.id }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-calendar-alt text-base"></i> Ro'yxatga olingan
                                </span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ formatDate(student.created_at) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-history text-base"></i> Oxirgi yangilanish
                                </span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ formatDate(student.updated_at) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4 bg-white dark:bg-black">
                        <div class="pb-4 border-b border-gray-200 dark:border-gray-700 mb-4">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tezkor harakatlar</h2>
                        </div>
                        <div class="space-y-3">
                            <Link :href="route('students.edit', student.id)" class="flex items-center w-full text-left p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 text-gray-700 dark:text-gray-300 text-sm font-medium">
                                <i class="fas fa-pen mr-3 text-base"></i> Tarbiyalanuvchini tahrirlash
                            </Link>
                            <Link href="#" class="flex items-center w-full text-left p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 text-gray-700 dark:text-gray-300 text-sm font-medium">
                                <i class="fas fa-users-class mr-3 text-base"></i> Katta guruh tafsilotlarini ko‘rish
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 text-gray-600 dark:text-gray-400 bg-white dark:bg-black">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2 flex items-center gap-2">
                    <i class="fas fa-cog"></i> Tarbiyalanuvchi boshqaruvi
                </h3>
                <p>Tarbiyalanuvchi kirishini va ruxsatlarini boshqarishingiz mumkin.</p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Student {
    id: number;
    full_name: string;
    phone: string;
    birth_date: string | null;
    balance: number;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    student: Student;
}>();

const formatDate = (dateString: string) => {
    if (!dateString) return 'Berilmagan';
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) {
            return 'Noma’lum sana';
        }
        // O'zbekcha format (DD.MM.YYYY) saqlanib qoldi
        return date.toLocaleDateString('uz-UZ', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
        });
    } catch (e) {
        console.error("Noto'g'ri sana satri:", dateString, e);
        return 'Xato sana';
    }
};

const formatCurrency = (amount: number) => {
    if (typeof amount !== 'number') return 'Noma\'lum';
    const formattedAmount = new Intl.NumberFormat('uz-UZ', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
    return `${formattedAmount} UZS`;
};

const confirmDelete = (id: number) => {
    if (confirm(`Siz rostdan ham "${props.student.full_name}" tarbiyalanuvchini o'chirishni xohlaysizmi? Bu amalni qaytarib bo'lmaydi!`)) {
        router.delete(route('students.destroy', id), {
            onSuccess: () => {
                router.visit(route('students.index'), {
                    onFinish: () => alert('Tarbiyalanuvchi muvaffaqiyatli o‘chirildi va ro‘yxatga qaytarildingiz.'),
                });
            },
            onError: (errors) => {
                console.error('Tarbiyalanuvchini o‘chirishda xatolik yuz berdi:', errors);
                let errorMessage = 'Tarbiyalanuvchini o‘chirishda xatolik yuz berdi.';
                if (errors && Object.keys(errors).length > 0) {
                    errorMessage += "\n" + Object.values(errors).join("\n");
                } else {
                    errorMessage += " Ma'lumot topilmadi yoki boshqa server xatosi.";
                }
                alert(errorMessage);
            }
        });
    }
};

const breadcrumbs = computed(() => [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Tarbiyalanuvchilar', href: '/students' },
    { title: props.student.full_name || 'Ma\'lumotlar', href: route('students.show', props.student.id) }
]);
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');


</style>
