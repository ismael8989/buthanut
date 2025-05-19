import styles from '../styles/Signup.module.css';

const Signup = function() {

    return (
        <div className={styles.container}>

            <div className={styles.formElements}>
                <label for="firstname">الاسم الشخصي</label>
                <input name="firstname" id="firstname" required/>
                <label for="lastname">الاسم العائلي</label>
                <input name="lastname" id="lastname" required/>
            </div>

            <div className={styles.nextButton}>
                <button>التالي <img src="/arrow.svg" width="12px" /></button>
            </div>

            <div className={styles.steps}>
                <div className={styles.active}></div>
                <div></div>
                <div></div>
                <div></div>
            </div>

        </div>
    );
}

export default Signup;