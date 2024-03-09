<template>

    <ElCard shadow="never">

        <div>
            <div>
                {{ groupLunarDate() }}
            </div>

            <div class="flex flex-row gap-2 w-500px">
                <ElSelect v-model="value1" placeholder="请选择年份">
                    <ElOption v-for="item in Lyears" :key="item.value" :label="item.label" :value="item.value">
                    </ElOption>
                </ElSelect>
                <ElSelect v-model="value2" placeholder="请选择月份">
                    <ElOption v-for="item in LMonths" :key="item.value" :label="item.label" :value="item.value">
                    </ElOption>
                </ElSelect>
                <ElSelect v-model="value3" placeholder="请选择日期">
                    <ElOption v-for="item in getLunarDays(value1, value2)" :key="item.value" :label="item.label"
                        :value="item.value"></ElOption>
                </ElSelect>
            </div>

            <!-- <ElDatePicker v-model="value1" type="date" :popper-options="{}" popper-class="datepicker" placeholder="选择日期">
            <template #default="cell">
                <div class="flex flex-col items-center justify-center">
                    <span>
                        {{ cell.text }}
                    </span>
                    <span class="text-xs text-gray">{{ tolunar(cell) }}</span>
                </div>
            </template>
</ElDatePicker> -->
        </div>

    </ElCard>

</template>


<script>
import calendar from 'js-calendar-converter';
import dayjs from 'dayjs'
export default {
    components: {},
    props: {},
    data() {
        return {
            value1: '',
            value2: '',
            value3: '',
            Lyears: [],
            LMonths: [],
        };
    },
    watch: {},
    computed: {},
    methods: {
        tolunar(data) {
            let date = dayjs(data.date);
            console.log(data);
            let year = date.year();
            let month = date.month() + 1;
            let day = date.date();
            console.log(calendar)
            let res = calendar.solar2lunar(year, month, day);
            return res['IMonthCn'] + res['IDayCn']
        },
        toLunarFormat(data) {
            let date = dayjs(data);
            let year = date.year();
            let month = date.month() + 1;
            let day = date.date();
            let res = calendar.solar2lunar(year, month, day);
            return res['cYear'] + res['gzYear'] + '年' + res['IMonthCn'] + res['IDayCn']
        },
        getLunarDays(year, month) {
            let leapMonth = calendar.leapMonth(year);
            let count = 30;
            if (leapMonth === month) {
                count = calendar.leapDays(year);
            } else {
                count = calendar.monthDays(year, month);
            }
            return Array.from({ length: count }, (v, k) => k + 1).map((v) => {
                return {
                    value: v,
                    label: calendar.toChinaDay(v)
                }
            });
        },
        groupLunarDate() {
            let year = this.value1;
            let chinaYear = this.Lyears.find((v) => v.value === year)?.label || "";
            let month = this.value2;
            let chinaMonth = this.LMonths.find((v) => v.value === month)?.label || "";
            let day = this.value3;
            let chinaDay = this.getLunarDays(year, month).find((v) => v.value === day)?.label || "";
            return `${chinaYear} ${chinaMonth} ${chinaDay}`
        }
    },
    created() { },
    mounted() {

        const toChinaNum = (num) => {
            let arr = ['零', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十'];
            return arr[num * 1];
        }

        const splitStrToChinaNum = (str) => {
            str = str + "";
            let arr = str.split('');
            return arr.map((v) => {
                return toChinaNum(v);
            }).join('');
        }

        let nowYear = dayjs().year();
        this.Lyears = Array.from({ length: 100 }, (v, k) => nowYear - k).map((v) => {
            return {
                value: v,
                label: splitStrToChinaNum(v) + " " + calendar.toGanZhiYear(v) + '年 ' + calendar.getAnimal(v)
            }
        })

        let currentMonth = dayjs().month() + 1;
        this.LMonths = Array.from({ length: 12 }, (v, k) => k + 1).map((v) => {
            return {
                value: v,
                label: calendar.toChinaMonth(v)
            }
        })
    }
};
</script>

<style lang="scss" scoped></style>