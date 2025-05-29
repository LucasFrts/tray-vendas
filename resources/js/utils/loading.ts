import Swal from "sweetalert2";
class TimerInterval {
    title: string;
    html: string;

    constructor(title: string = 'Carregando', html: string = '') {
        this.title = title;
        this.html = html;
    }

    timer(timer: number = 2000, timerProgressBar: boolean = true, openFunc?: () => void, resultFunc?: () => void): void {
        Swal.fire({
            title: this.title,
            html: this.html,
            timer: timer,
            timerProgressBar: timerProgressBar,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                if (typeof openFunc === 'function') {
                    openFunc();
                }
            }
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('I was closed by the timer');
                if (typeof resultFunc === 'function') {
                    resultFunc();
                }
            }
        });
    }

    wait(openFunc?: () => void, resultFunc?: () => void): void {
        setTimeout(() => {
            Swal.fire({
                title: this.title,
                html: this.html,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    if (typeof openFunc === 'function') {
                        openFunc();
                    }
                }
            }).then(() => {
                if (typeof resultFunc === 'function') {
                    resultFunc();
                }
            });
        }, 100);
    }

    stop(): void {
        Swal.close();
    }
}

const LoadingService = new TimerInterval();
export default LoadingService;
