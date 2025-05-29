import Swal from 'sweetalert2'

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  iconColor: 'white',
  customClass: {
    popup: 'colored-toast',
  },
  showConfirmButton: false,
  timer: 1500,
  timerProgressBar: true,
})

const success = (data: any) => Toast.fire({ icon: 'success', title: data })
const error = (data: any) => Toast.fire({ icon: 'error', title: data })
const warning = (data: any) => Toast.fire({ icon: 'warning', title: data })
const info = (data: any) => Toast.fire({ icon: 'info', title: data })
const question = (data: any) => Toast.fire({ icon: 'question', title: data })

const ToastService = {
  success,
  error,
  warning,
  info,
  question,
}
export default ToastService