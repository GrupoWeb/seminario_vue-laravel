<script>
import { Line } from 'vue-chartjs'
import { getStyle, hexToRgba } from '@coreui/utils/src'
import { CustomTooltips } from '@coreui/coreui-plugin-chartjs-custom-tooltips'
import { random } from '@/shared/utils'

export default {
  extends: Line,
  props: ['height'],
  mounted () {
    const brandSuccess = getStyle('--success') || '#4dbd74'
    const brandInfo = getStyle('--info') || '#20a8d8'
    const brandDanger = getStyle('--danger') || '#f86c6b'

    let elements = 27
    const data1 = []
    const data2 = []
    const data3 = []

    for (let i = 0; i <= elements; i++) {
      data1.push(random(50, 200))
      data2.push(random(80, 100))
      data3.push(65)
    }

  }
}
</script>
