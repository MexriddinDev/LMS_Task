<template>
    <Head title="To'lov qo'shish" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-4 text-gray-800 dark:text-gray-200 max-w-4xl mx-auto">
            <div class="flex items-center gap-4">
                <Link :href="route('payments.index')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                    <i class="fas fa-arrow-left text-xl"></i>
                </Link>
                <h1 class="text-2xl font-bold">To'lov qo'shish</h1>
            </div>

            <p class="text-gray-600 dark:text-gray-400">Yangi to'lov yarating</p>

            <form @submit.prevent="submitForm" class="space-y-6">
                <div class="p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">To'lov ma'lumotlari</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">O'quvchini tanlang</label>
                            <select
                                id="student_id"
                                v-model="form.student_id"
                                @change="onStudentChange"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200 bg-white dark:bg-gray-950"
                                required
                            >
                                <option value="">--- O'quvchini tanlang ---</option>
                                <option v-for="student in students" :key="student.id" :value="student.id">
                                    {{ student.full_name }} (Balans: {{ formatCurrency(student.balance) }})
                                </option>
                            </select>
                            <p v-if="form.errors.student_id" class="text-red-500 text-sm mt-1">{{ form.errors.student_id }}</p>
                        </div>

                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To'lov sanasi</label>
                            <input
                                type="date"
                                id="date"
                                v-model="form.date"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                                required
                            >
                            <p v-if="form.errors.date" class="text-red-500 text-sm mt-1">{{ form.errors.date }}</p>
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To'lov turi</label>
                            <select
                                id="type"
                                v-model="form.type"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200 bg-white dark:bg-gray-950"
                                required
                            >
                                <option value="">--- To'lov turini tanlang ---</option>
                                <option value="debt">Qarz to'lash</option>
                                <option value="balance">Balansga qo'shish</option>
                            </select>
                            <p v-if="form.errors.type" class="text-red-500 text-sm mt-1">{{ form.errors.type }}</p>
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To'lov miqdori</label>
                            <input
                                type="number"
                                id="amount"
                                v-model.number="form.amount"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                                placeholder="0"
                                required
                                min="0"
                            >
                            <p v-if="form.errors.amount" class="text-red-500 text-sm mt-1">{{ form.errors.amount }}</p>
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To'lov usuli</label>
                            <select
                                id="payment_method"
                                v-model="form.payment_method"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200 bg-white dark:bg-gray-950"
                                required
                            >
                                <option value="">--- To'lov usulini tanlang ---</option>
                                <option value="cash">Naqd pul</option>
                                <option value="card">Plastik karta</option>
                                <option value="transfer">Bank o'tkazmasi</option>
                                <option value="online">Online to'lov</option>
                            </select>
                            <p v-if="form.errors.payment_method" class="text-red-500 text-sm mt-1">{{ form.errors.payment_method }}</p>
                        </div>

                        <div v-if="form.type === 'debt' && selectedStudentDebts.length > 0" class="md:col-span-2">
                            <label for="debt_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Qarzni tanlang</label>
                            <select
                                id="debt_id"
                                v-model="form.debt_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200 bg-white dark:bg-gray-950"
                            >
                                <option value="">--- Qarzni tanlang (ixtiyoriy) ---</option>
                                <option v-for="debt in selectedStudentDebts" :key="debt.id" :value="debt.id">
                                    {{ debt.group?.name || 'Guruh nomi yo\'q' }} - 
                                    Qarz: {{ formatCurrency(debt.amount) }} | 
                                    To'langan: {{ formatCurrency(debt.paid_amount) }} | 
                                    Qolgan: {{ formatCurrency(debt.amount - debt.paid_amount) }}
                                </option>
                            </select>
                            <p v-if="form.errors.debt_id" class="text-red-500 text-sm mt-1">{{ form.errors.debt_id }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Izoh (ixtiyoriy)</label>
                            <textarea
                                id="note"
                                v-model="form.note"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                                placeholder="To'lov haqida qo'shimcha ma'lumot..."
                            ></textarea>
                            <p v-if="form.errors.note" class="text-red-500 text-sm mt-1">{{ form.errors.note }}</p>
                        </div>
                    </div>
                </div>

                <!-- Student Debts Display -->
                <div v-if="selectedStudent && selectedStudentDebts.length > 0" class="p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">O'quvchi qarzlari</h2>
                    <div class="space-y-2">
                        <div v-for="debt in selectedStudentDebts" :key="debt.id" 
                             class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ debt.group?.name || 'Guruh nomi yo\'q' }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Jami qarz: {{ formatCurrency(debt.amount) }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        To'langan: {{ formatCurrency(debt.paid_amount) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-red-600 dark:text-red-400">
                                        Qolgan: {{ formatCurrency(debt.amount - debt.paid_amount) }}
                                    </p>
                                    <button
                                        type="button"
                                        @click="fillDebtAmount(debt)"
                                        class="text-sm text-blue-500 hover:text-blue-700 mt-1"
                                    >
                                        To'liq to'lash
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <Link :href="route('payments.index')" class="text-gray-700 px-5 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors duration-200">
                        Bekor qilish
                    </Link>
                    <button type="submit" :disabled="form.processing" class="bg-white text-black px-5 py-2 rounded-lg hover:bg-gray-100 dark:bg-white dark:text-black dark:hover:bg-gray-200 transition-colors duration-200">
                        <span v-if="form.processing">Yaratilmoqda...</span>
                        <span v-else>To'lovni yaratish</span>
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface Group {
    id: number;
    name: string;
}

interface Debt {
    id: number;
    amount: number;
    paid_amount: number;
    is_paid: boolean;
    group?: Group;
}

interface Student {
    id: number;
    full_name: string;
    balance: number;
    groups?: Group[];
    debts?: Debt[];
}

const props = defineProps<{
    students: Student[];
}>();

const form = useForm({
    student_id: '',
    amount: 0,
    date: new Date().toISOString().split('T')[0],
    type: '',
    note: '',
    debt_id: null as number | null,
    payment_method: '',
});

const selectedStudent = computed(() => {
    if (!form.student_id) return null;
    return props.students.find(s => s.id === parseInt(form.student_id)) || null;
});

const selectedStudentDebts = computed(() => {
    if (!selectedStudent.value) return [];
    return selectedStudent.value.debts || [];
});

const onStudentChange = () => {
    form.debt_id = null;
    form.type = '';
    form.amount = 0;
};

const fillDebtAmount = (debt: Debt) => {
    form.type = 'debt';
    form.debt_id = debt.id;
    form.amount = debt.amount - debt.paid_amount;
};

const formatCurrency = (amount: number) => {
    if (typeof amount !== 'number') return 'Noma\'lum';
    const formattedAmount = new Intl.NumberFormat('uz-UZ', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
    return `${formattedAmount} UZS`;
};

const submitForm = () => {
    form.post(route('payments.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};

const breadcrumbs = computed(() => [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'To\'lovlar', href: route('payments.index') },
    { title: 'To\'lov qo\'shish', href: route('payments.create') }
]);
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>