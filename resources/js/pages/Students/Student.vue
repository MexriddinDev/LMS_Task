<template>
    <Head title="Tarbiaylanuvchilar" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-4 text-gray-800 dark:text-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Tarbiyalanuvchilar</h1>
                <Link :href="route('students.create')">
                    <Button class="bg-white text-black border border-gray-300 hover:bg-gray-100 dark:bg-white dark:text-black dark:border-gray-600 dark:hover:bg-gray-200 px-5 py-2.5 rounded-lg transition-colors duration-200">
                        + Tarbiyalanuvchi qo‘shish
                    </Button>
                </Link>
            </div>

            <p class="text-gray-600 dark:text-gray-400">Tarbiyalanuvchilar ma'lumotlari va ro'yxatga olishni boshqarish</p>

            <div class="grid grid-cols-3 gap-4 ">
                <Card class="border-gray-200 shadow-sm dark:border-gray-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">Jami tarbiyalanuvchilar</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <h2 class="text-2xl font-semibold text-black dark:text-white">{{ displayedStudents.length }}</h2>
                    </CardContent>
                </Card>
                <Card class="border-green-200 shadow-sm dark:border-green-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">Faol tarbyalanuvchilar</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <h2 class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ displayedStudents.length }}</h2>
                    </CardContent>
                </Card>
                <Card class="border-red-200 shadow-sm dark:border-red-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">Nofaol tarbiyalanuvchilar</CardTitle>
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
                        <th class="p-3 text-left">To'liq ism</th>
                        <th class="p-3 text-left">Telefon</th>
                        <th class="p-3 text-left">Guruh</th>
                        <th class="p-3 text-left">Tug'ilgan sana</th>
                        <th class="p-3 text-left">Balans</th>
                        <th class="p-3 text-left">Ro‘yxatga olingan</th>
                        <th class="p-3 text-left">Harakatlar</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="student in displayedStudents" :key="student.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="p-3 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full border border-gray-400 flex items-center justify-center dark:border-gray-500">
                                <i class="fas fa-user text-gray-500 text-sm dark:text-gray-400"></i>
                            </div>
                            <div>
                                <div class="font-medium text-black dark:text-white">{{ student.full_name }}</div>
                            </div>
                        </td>
                        <td class="p-3 text-sm">
                            <div class="text-gray-500 dark:text-gray-400">{{ student.phone }}</div>
                        </td>
                        <td class="p-3 text-sm">
                            <div class="text-gray-500 dark:text-gray-400">
                                <template v-if="student.groups && student.groups.length > 0">
                                    <div
                                        v-for="group in student.groups"
                                        :key="group.id"
                                        class="inline-block bg-gray-200 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-gray-700 dark:text-gray-200 mr-1 mb-1"
                                    >
                                        {{ group.name }}
                                    </div>
                                </template>

                            </div>
                        </td>

                        <td class="p-3 text-sm">
                            <div class="text-gray-500 dark:text-gray-400">{{ student.birth_date ? formatDate(student.birth_date) : 'Berilmagan' }}</div>
                        </td>
                        <td class="p-3 text-sm">
                            <div class="text-gray-500 dark:text-gray-400">{{ formatCurrency(student.balance) }}</div>
                        </td>
                        <td class="p-3 text-sm">
                            <div class="text-gray-500 dark:text-gray-400">{{ formatDate(student.created_at) }}</div>
                        </td>
                        <td class="p-3 flex items-center gap-2">
                            <Link :href="route('students.show', student.id)" title="Ko'rish">
                                <Button variant="ghost" size="icon" title="Ko‘rish" class="dark:text-gray-400 dark:hover:bg-gray-700">
                                    <i class="fas fa-eye text-gray-600 text-sm dark:text-gray-400"></i>
                                </Button>
                            </Link>

                            <Link :href="route('students.edit', student.id)" title="Tahrirlash">
                                <Button variant="ghost" size="icon" class="dark:text-gray-400 dark:hover:bg-gray-700">
                                    <i class="fas fa-pen text-gray-600 text-sm dark:text-gray-400"></i>
                                </Button>
                            </Link>
                            <Button variant="ghost" size="icon" title="O‘chirish" class="dark:text-gray-400 dark:hover:bg-gray-700"
                                    @click="confirmDelete(student.id)">
                                <i class="fas fa-trash text-red-500 text-sm dark:text-red-400"></i>
                            </Button>
                        </td>
                    </tr>
                    <tr v-if="displayedStudents.length === 0">
                        <td colspan="7" class="p-3 text-center text-gray-500 dark:text-gray-400">
                            Hozircha hech qanday tarbiyalanuvchi yo‘q.
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
import { ref } from 'vue'; // ref ni import qilish

// Interfeyslar definitsiyasi
interface Group {
    id: number;
    name: string;
}

interface Student {
    id: number;
    full_name: string;
    phone: string;
    birth_date: string | null;
    balance: number;
    created_at: string;
    // MUHIM: Student modelida 'groups' munosabati eager load qilinganda bu yerda bo'lishi kerak
    groups?: Group[]; // Talaba tegishli bo'lgan guruhlar massivi
}

// GroupStudent interfeysi (agar kerak bo'lsa, lekin bu komponentda to'g'ridan-to'g'ri ishlatilmayapti)
interface GroupStudent {
    id: number;
    group_id: number;
    student_id: number;
    created_at: string;
    group?: Group;
    student?: Student;
}

// Props'larni qabul qilish
const props = defineProps<{
    students: Student[];
    groups: Array<{ id: number; name: string }>;
}>();

// displayedStudents ni props.students bilan boshlash
const displayedStudents = ref<Student[]>(props.students);

// Sanani formatlash funksiyasi
const formatDate = (dateString: string) => {
    if (!dateString) return '';
    try {
        return new Date(dateString).toLocaleDateString('uz-UZ', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
        });
    } catch (e) {
        console.error("Invalid date string:", dateString, e);
        return 'Noma’lum sana';
    }
};

// Pul miqdorini formatlash funksiyasi
const formatCurrency = (amount: number) => {
    const formattedAmount = new Intl.NumberFormat('uz-UZ', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
    return `${formattedAmount} UZS`;
};

// O'chirishni tasdiqlash funksiyasi
const confirmDelete = (id: number) => {
    // alert() o'rniga ogohlantirish uchun custom modal yoki toast ishlatish tavsiya etiladi
    // Hozircha oddiy confirm() ishlatilgan.
    if (confirm("Haqiqatan ham ushbu tarbiaylanuvchini o'chirmoqchimisiz?")) {
        router.delete(route('students.destroy', id), {
            onSuccess: () => {
                // Muvaffaqiyatli o'chirilgandan so'ng, talabani ro'yxatdan olib tashlaymiz
                displayedStudents.value = displayedStudents.value.filter(student => student.id !== id);
                console.log('Tarbiaylanuvchi muvaffaqiyatli o‘chirildi! 👍');
                // Foydalanuvchiga xabar berish uchun toast notification qo'shishingiz mumkin
            },
            onError: (errors) => {
                console.error('Tarbiaylanuvchini o‘chirishda xatolik yuz berdi: ❗', errors);
                let errorMessage = 'Tarbiaylanuvchini o‘chirishda xatolik yuz berdi.';
                if (errors && Object.keys(errors).length > 0) {
                    errorMessage += "\n" + Object.values(errors).join("\n");
                } else {
                    errorMessage += " Ma'lumot topilmadi yoki boshqa server xatosi.";
                }
                // alert() o'rniga custom modal ishlatish tavsiya etiladi
                alert(errorMessage);
            }
        });
    }
};

// Breadcrumbs ma'lumotlari
const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Tarbiyalanuvchilar', href: '/students' }
]
</script>

<style scoped>
/* Font Awesome ikonkalari uchun CSS import qilinadi */
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

/* Guruh nomlari uchun kichik stil */
.inline-block {
    display: inline-block;
    margin-right: 4px; /* Kichik bo'shliq */
    margin-bottom: 4px; /* Kichik bo'shliq */
}
</style>
