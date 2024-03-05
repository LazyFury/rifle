<template>
<div>
    <div class="grid grid-cols-2 gap-2">
        <ElCard shadow="never">
        <div slot="header" class="mb-2">
            <span class="text-2xl font-bold">系统信息</span>
        </div>
        <ElDivider/>
        <div>
            <div class="grid grid-cols-1 gap-2">
                <div>
                    <span class="mr-2 text-gray">开机时间:</span>
                    <span class="text-dark" :key="renderKey">{{ uptime() }}</span>
                </div>

                <div class="" v-for="key in infoKeys" :key="key.key">
                    <span class="mr-2 text-gray">{{ key.label }}: </span>
                    <span class="text-dark">{{ info[key.key] }}</span>
                </div>
            </div>
        </div>
    </ElCard>

    <!-- cpu  -->
    <ElCard shadow="never">
        <div slot="header" class="mb-2">
            <span class="text-2xl font-bold">CPU</span>
        </div>
        <div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <span class="mr-2">使用率:</span>
                    <span>{{ monitorData.cpu_rate}}</span>
                </div>
                <div>
                    <span class="mr-2">核心数:</span>
                    <span>{{ monitorData.physical_cpus }}</span>
                </div>
            </div>
        </div>
    </ElCard>

    <!-- memory -->
    <ElCard shadow="never">
        <div slot="header" class="mb-2">
            <span class="text-2xl font-bold">内存</span>
        </div>
        <div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <span class="mr-2">使用率:</span>
                    <span>{{ monitorData.memory }}%</span>
                </div>
                <div>
                    <span class="mr-2">总内存:</span>
                    <span>{{ monitorData.memory_all_GB }}GB</span>
                </div>
            </div>
        </div>
    </ElCard>

    <!-- dist  -->
    <ElCard shadow="never">
        <div slot="header" class="mb-2">
            <span class="text-2xl font-bold">磁盘</span>
        </div>
        <div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <span class="mr-2">已使用:</span>
                    <span>{{ Number(monitorData.dist_GB || 0).toFixed(2) }}GB</span>
                </div>
                <div>
                    <span class="mr-2">总磁盘:</span>
                    <span>{{ Number(monitorData.dist_all_GB || 0).toFixed(2) }}GB</span>
                </div>
            </div>
        </div>
    </ElCard>
    </div>

</div>
</template>
<script>
import { request } from '@/api/request'
import { ElDescriptions, ElDivider } from 'element-plus';
import config from '../../config';
import dayjs from 'dayjs'
export default {
  components: { ElDivider, ElDescriptions },
  props: {},
  data() {
    return {
        info:{},
        monitor:{},
        monitorData:{},
        renderKey: 0,
        infoKeys:[
            {
                key:"system",label:"操作系统",
            },
            
            {
                key:"machine",label:"硬件架构",
            },
            {
                key:"arch",label:"CPU架构",
            },
            {
                key:"platform",label:"平台",
            }
        ]
    };
  },
  watch: {},
  computed: {
    
  },
  methods: {
    uptime(){
        let bootTime = dayjs(this.info.bootTime)
        let diffunix = dayjs().diff(bootTime)
        return dayjs(diffunix).format('DD 天 HH 时 mm 分 ss 秒')
    }
  },
  created() {},
  mounted() {
    request({
        url: '/system-info',
        method: 'get'
    }).then(res => {
        this.info = res.data?.data
    })

    setInterval(() => {
        this.renderKey++
    }, 1000);

    // sse with data 
    this.monitor = new EventSource(config.url.API_URL + '/system-monitor');
    this.monitor.onmessage = (event) => {
        this.monitorData = JSON.parse(event.data)
    }
    this.monitor.onerror = (event) => {
        console.log('error', event)
    }

    this.$router.beforeEach((to, from, next) => {
        if (to.path !== '/dev/system-monitor') {
            this.monitor.close()
        }
        next()
    })

  },
};
</script>
<style lang="scss" scoped>
</style>