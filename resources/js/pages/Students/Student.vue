<template>
    <Head title="Tarbiaylanuvchilar" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-4 text-gray-800 dark:text-gray-200">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">O'quvchilar</h1>
                <Link :href="route('students.create')">
                    <Button class="bg-white text-black border border-gray-300 hover:bg-gray-100 dark:bg-white dark:text-black dark:border-gray-600 dark:hover:bg-gray-200 px-5 py-2.5 rounded-lg transition-colors duration-200">
                        + O'quvchi qo‘shish
                    </Button>
                </Link>
            </div>


            <div class="grid grid-cols-3 gap-4 ">
                <Card class="border-gray-200 shadow-sm dark:border-gray-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">Jami o'quvchilar</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <h2 class="text-2xl font-semibold text-black dark:text-white">{{ displayedStudents.length }}</h2>
                    </CardContent>
                </Card>
                <Card class="border-green-200 shadow-sm dark:border-green-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">Faol o'quvchilar</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <h2 class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ displayedStudents.length }}</h2>
                    </CardContent>
                </Card>
                <Card class="border-red-200 shadow-sm dark:border-red-700">
                    <CardHeader>
                        <CardTitle class="text-gray-500 text-base dark:text-gray-400">Nofaol o'quvchilar</CardTitle>
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
                            <div class="text-gray-500 dark:text-gray-400">{{ formatPhone(student.phone) }}</div>
                        </td>
                        <td class="p-3 text-sm">
                            <div class="flex flex-wrap gap-1">
                                <template v-if="student.groups && student.groups.length > 0">
                              <span
                                  v-for="group in student.groups"
                                  :key="group.id"
                                  class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                              >
                                {{ group.name }}
                              </span>
                                </template>
                                <span v-else class="text-gray-400 italic">Guruh biriktirilmagan</span>
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
                        <td class="p-3">
                            <div class="flex items-center gap-2">
                                <!-- Ko‘rish -->
                                <Link :href="route('students.show', student.id)" title="Ko‘rish">
                                    <Button variant="outline" size="icon" class="dark:hover:bg-gray-700">
                                        <i class="fas fa-eye text-gray-600 text-sm dark:text-gray-300"></i>
                                    </Button>
                                </Link>

                                <!-- Tahrirlash -->
                                <Link :href="route('students.edit', student.id)" title="Tahrirlash">
                                    <Button variant="outline" size="icon" class="dark:hover:bg-gray-700">
                                        <i class="fas fa-pen text-gray-600 text-sm dark:text-gray-300"></i>
                                    </Button>
                                </Link>

                                <!-- O‘chirish -->
                                <Button variant="outline" size="icon" title="O‘chirish"
                                        class="dark:hover:bg-gray-700"
                                        @click="confirmDelete(student.id)">
                                    <i class="fas fa-trash text-red-500 text-sm dark:text-red-400"></i>
                                </Button>
                            </div>
                        </td>

                    </tr>
                    <tr v-if="displayedStudents.length === 0">
                        <td colspan="7" class="p-3 text-center text-gray-500 dark:text-gray-400">
                            Hozircha hech qanday o'quvchi yo‘q.
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
    groups?: Group[];
}

interface GroupStudent {
    id: number;
    group_id: number;
    student_id: number;
    created_at: string;
    group?: Group;
    student?: Student;
}

const props = defineProps<{
    students: Student[];
    groups: Array<{ id: number; name: string }>;
}>();

const displayedStudents = ref<Student[]>(props.students);

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

const formatCurrency = (amount: number) => {
    const formattedAmount = new Intl.NumberFormat('uz-UZ', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
    return `${formattedAmount} UZS`;
};

const confirmDelete = (id: number) => {
    if (confirm("Haqiqatan ham ushbu o'quvchini o'chirmoqchimisiz?")) {
        router.delete(route('students.destroy', id), {
            onSuccess: () => {
                displayedStudents.value = displayedStudents.value.filter(student => student.id !== id);
                console.log('Oquvchi muvaffaqiyatli o‘chirildi! 👍');
            },
            onError: (errors) => {
                console.error('Oquvchini o‘chirishda xatolik yuz berdi: ❗', errors);
                let errorMessage = 'Oquvchini o‘chirishda xatolik yuz berdi.';
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
// Breadcrumbs ma'lumotlari
const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Oquvchilar', href: '/students' }
]
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

.inline-block {
    display: inline-block;
    margin-right: 4px;
    margin-bottom: 4px;
}
</style>
