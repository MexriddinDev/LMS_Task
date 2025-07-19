<template>
    <Head title="Guruh tahrirlash" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-4 text-gray-800 dark:text-gray-200 max-w-4xl mx-auto">
            <div class="flex items-center gap-4">
                <Link :href="route('groups.index')" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                    <i class="fas fa-arrow-left text-xl"></i>
                </Link>
                <h1 class="text-2xl font-bold">Guruh tahrirlash</h1>
            </div>

            <p class="text-gray-600 dark:text-gray-400">O'quv guruhining ma'lumotlarini yangilang.</p>

            <form @submit.prevent="onSubmit" class="space-y-6">
                <div class="p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Guruh ma'lumotlari</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <FormField name="name" v-slot="{ field }">
                            <FormItem>
                                <FormLabel for="group_name">Guruh nomi</FormLabel>
                                <FormControl>
                                    <input
                                        type="text"
                                        id="group_name"
                                        v-model="field.value"
                                        @input="field.handleChange"
                                        @blur="field.handleBlur"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                        placeholder="Guruh nomini kiriting"
                                        required
                                    >
                                </FormControl>
                                <FormMessage :name="field.name" />
                            </FormItem>
                        </FormField>

                        <FormField name="teacher_id" v-slot="{ field }">
                            <FormItem>
                                <FormLabel for="teacher_select">O'qituvchi</FormLabel>
                                <FormControl>
                                    <select
                                        id="teacher_select"
                                        v-model="field.value"
                                        @change="field.handleChange"
                                        @blur="field.handleBlur"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white text-black dark:bg-black dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none"
                                        required
                                    >
                                        <option value="" disabled class="text-gray-500 dark:text-gray-400">O'qituvchi tanlang</option>
                                        <option
                                            v-for="teacher in teachers"
                                            :key="teacher.id"
                                            :value="teacher.id"
                                            class="text-black dark:text-white"
                                        >
                                            {{ teacher.name }}
                                        </option>
                                    </select>
                                </FormControl>
                                <FormMessage :name="field.name" />
                            </FormItem>
                        </FormField>

                        <FormField name="monthly_fee" v-slot="{ field }">
                            <FormItem>
                                <FormLabel for="monthly_fee">Oylik to'lov</FormLabel>
                                <FormControl>
                                    <input
                                        type="number"
                                        id="monthly_fee"
                                        v-model="field.value"
                                        @input="field.handleChange"
                                        @blur="field.handleBlur"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                        placeholder="Oylik to'lovni kiriting"
                                        required
                                    >
                                </FormControl>
                                <FormMessage :name="field.name" />
                            </FormItem>
                        </FormField>

                        <FormField name="start_date">
                            <FormItem class="flex flex-col">
                                <FormLabel>Boshlanish sanasi</FormLabel>
                                <Popover>
                                    <PopoverTrigger as-child>
                                        <FormControl>
                                            <Button
                                                variant="outline"
                                                :class="cn(
                                                    'w-full ps-3 text-start font-normal',
                                                    'border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500',
                                                    !startDateValue && 'text-muted-foreground'
                                                )"
                                            >
                                                <span>{{ startDateValue ? df.format(toDate(startDateValue)) : "Sana tanlang" }}</span>
                                                <CalendarIcon class="ms-auto h-4 w-4 opacity-50" />
                                            </Button>
                                        </FormControl>
                                    </PopoverTrigger>
                                    <PopoverContent class="w-auto p-0">
                                        <Calendar
                                            v-model:placeholder="startDatePlaceholder"
                                            :model-value="startDateValue"
                                            calendar-label="Boshlanish sanasi"
                                            initial-focus
                                            :min-value="new CalendarDate(1900, 1, 1)"
                                            :max-value="today(getLocalTimeZone())"
                                            @update:model-value="(v) => {
                                                if (v) {
                                                    setFieldValue('start_date', v.toString())
                                                } else {
                                                    setFieldValue('start_date', undefined)
                                                }
                                            }"
                                        />
                                    </PopoverContent>
                                </Popover>
                                <FormDescription>
                                    Guruhning boshlanish sanasi.
                                </FormDescription>
                                <FormMessage name="start_date" />
                            </FormItem>
                        </FormField>

                        <FormField name="date">
                            <FormItem class="flex flex-col">
                                <FormLabel>Hozirgi sana</FormLabel>
                                <Popover>
                                    <PopoverTrigger as-child>
                                        <FormControl>
                                            <Button
                                                variant="outline"
                                                :class="cn(
                                                    'w-full ps-3 text-start font-normal',
                                                    'border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500',
                                                    !currentDateValue && 'text-muted-foreground'
                                                )"
                                            >
                                                <span>{{ currentDateValue ? df.format(toDate(currentDateValue)) : "Sana tanlang" }}</span>
                                                <CalendarIcon class="ms-auto h-4 w-4 opacity-50" />
                                            </Button>
                                        </FormControl>
                                    </PopoverTrigger>
                                    <PopoverContent class="w-auto p-0">
                                        <Calendar
                                            v-model:placeholder="currentDatePlaceholder"
                                            :model-value="currentDateValue"
                                            calendar-label="Hozirgi sana"
                                            initial-focus
                                            :min-value="new CalendarDate(1900, 1, 1)"
                                            :max-value="today(getLocalTimeZone())"
                                            @update:model-value="(v) => {
                                                if (v) {
                                                    setFieldValue('date', v.toString())
                                                } else {
                                                    setFieldValue('date', undefined)
                                                }
                                            }"
                                        />
                                    </PopoverContent>
                                </Popover>
                                <FormDescription>
                                    Guruh yaratilgan sana.
                                </FormDescription>
                                <FormMessage name="date" />
                            </FormItem>
                        </FormField>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <Link :href="route('groups.index')" class="text-gray-700 px-5 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors duration-200">
                        Bekor qilish
                    </Link>
                    <Button type="submit" :disabled="isSubmitting" class="bg-white text-black px-5 py-2 rounded-lg hover:bg-gray-100 dark:bg-white dark:text-black dark:hover:bg-gray-200 transition-colors duration-200">
                        <span v-if="isSubmitting">Yangilanmoqda...</span>
                        <span v-else>Guruh yangilash</span>
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, defineComponent, h } from 'vue';
import { CalendarDate, DateFormatter, getLocalTimeZone, parseDate, today } from '@internationalized/date'
import { toTypedSchema } from '@vee-validate/zod'
import { CalendarIcon } from 'lucide-vue-next'
import { useForm, useField } from 'vee-validate'
import { z } from 'zod'
import { Button } from '@/components/ui/button'
import { Calendar } from '@/components/ui/calendar'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'

// Mock toDate function
function toDate(date: CalendarDate): Date {
    return new Date(date.year, date.month - 1, date.day);
}

// Utility to validate ISO 8601 date string
function isValidISODate(dateString: string): boolean {
    try {
        parseDate(dateString);
        return true;
    } catch {
        return false;
    }
}

// Basic `cn` utility function
type ClassValue = string | boolean | null | undefined | { [key: string]: ClassValue } | ClassValue[];
function cn(...inputs: ClassValue[]): string {
    const classes: string[] = [];
    for (const input of inputs) {
        if (typeof input === 'string' && input.trim() !== '') {
            classes.push(input.trim());
        } else if (typeof input === 'object' && input !== null) {
            if (Array.isArray(input)) {
                classes.push(cn(...input));
            } else {
                for (const key in input) {
                    if (input[key]) {
                        classes.push(key.trim());
                    }
                }
            }
        }
    }
    return classes.join(' ');
}

// Inline placeholder components for Shadcn UI form elements
const FormItem = defineComponent({
    setup(_, { slots }) {
        return () => h('div', { class: 'space-y-1' }, slots.default?.());
    },
});

const FormLabel = defineComponent({
    props: { for: String },
    setup(props, { slots }) {
        return () => h('label', { for: props.for, class: 'block text-sm font-medium text-gray-900 dark:text-white mb-1' }, slots.default?.());
    },
});

const FormControl = defineComponent({
    setup(_, { slots }) {
        return () => h('div', slots.default?.());
    },
});

const FormDescription = defineComponent({
    setup(_, { slots }) {
        return () => h('p', { class: 'text-gray-500 dark:text-gray-400 text-sm mt-1' }, slots.default?.());
    },
});

const FormMessage = defineComponent({
    props: { name: String },
    setup(props, { slots }) {
        const { errorMessage, meta } = useField(() => props.name as string);
        return () => {
            if (errorMessage.value && (meta.dirty || meta.touched)) {
                return h('p', { class: 'text-red-500 text-sm mt-1' }, errorMessage.value);
            }
            return slots.default?.();
        };
    },
});

const FormField = defineComponent({
    props: { name: { type: String, required: true } },
    setup(props, { slots }) {
        const { value, handleChange, handleBlur, errorMessage, meta } = useField(() => props.name as string);
        return () => slots.default?.({
            field: { name: props.name, value: value.value, handleChange, handleBlur },
            meta,
            errorMessage: errorMessage.value,
        });
    },
});

// Date formatter
const df = new DateFormatter('en-US', { dateStyle: 'long' });

// Define props with validation
const props = defineProps<{
    group: {
        id: number;
        name: string;
        teacher_id: number;
        monthly_fee: number;
        start_date: string;
        date: string;
    };
    teachers: {
        id: number;
        name: string;
    }[];
}>();

// Sanitize date props
const sanitizedStartDate = computed(() => isValidISODate(props.group.start_date) ? props.group.start_date : '');
const sanitizedDate = computed(() => isValidISODate(props.group.date) ? props.group.date : '');

// Zod schema for form validation
const formSchema = toTypedSchema(z.object({
    name: z.string().min(1, "Guruh nomi kiritilishi shart."),
    teacher_id: z.coerce.number().min(1, "O'qituvchi tanlanishi shart."),
    monthly_fee: z.coerce.number().min(0, "Oylik to'lov manfiy bo'lishi mumkin emas.").refine(val => val !== null && val !== undefined, "Oylik to'lov kiritilishi shart."),
    start_date: z.string().refine(v => v && isValidISODate(v), { message: "Boshlanish sanasi to'g'ri formatda bo'lishi shart (YYYY-MM-DD)." }),
    date: z.string().refine(v => v && isValidISODate(v), { message: "Hozirgi sana to'g'ri formatda bo'lishi shart (YYYY-MM-DD)." }),
}));

// VeeValidate form setup with sanitized initial values
const { handleSubmit, setFieldValue, values, errors, isSubmitting, resetForm } = useForm({
    validationSchema: formSchema,
    initialValues: {
        name: props.group.name,
        teacher_id: props.group.teacher_id.toString(),
        monthly_fee: props.group.monthly_fee,
        start_date: sanitizedStartDate.value,
        date: sanitizedDate.value,
    }
});

// Calendar related refs and computed properties for start_date
const startDatePlaceholder = ref();
const startDateValue = computed({
    get: () => values.start_date && isValidISODate(values.start_date) ? parseDate(values.start_date) : undefined,
    set: val => {
        if (val) {
            setFieldValue('start_date', val.toString());
        } else {
            setFieldValue('start_date', undefined);
        }
    }
});

// Calendar related refs and computed properties for date
const currentDatePlaceholder = ref();
const currentDateValue = computed({
    get: () => values.date && isValidISODate(values.date) ? parseDate(values.date) : undefined,
    set: val => {
        if (val) {
            setFieldValue('date', val.toString());
        } else {
            setFieldValue('date', undefined);
        }
    }
});

// Form submission handler
const onSubmit = handleSubmit(async (formValues) => {
    const payload = {
        ...formValues,
        teacher_id: Number(formValues.teacher_id),
        monthly_fee: Number(formValues.monthly_fee)
    };

    router.put(route('groups.update', props.group.id), payload, {
        onSuccess: () => {
            console.log('Guruh muvaffaqiyatli yangilandi!');
            resetForm({ values: formValues });
        },
        onError: (inertiaErrors) => {
            console.error('Guruh yangilashda xatolik yuz berdi:', inertiaErrors);
        },
    });
});

// Breadcrumbs
const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Guruhlar', href: '/groups' },
    { title: 'Guruh tahrirlash', href: `/groups/${props.group.id}/edit` }
];
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
