import styles from '../styles/Home.module.css';
import { useNavigate } from 'react-router-dom'; 

const Home = function() {

    const navigate = useNavigate();

    return (
        <div className={styles.container}>
            <button onClick={() => navigate('/login')}><img src="/profile.svg" width="12px" /> تسجيل الدخول</button>
            <button onClick={() => navigate('/signup')}><img src="/register.svg" width="12px" /> إنشاء حساب</button>
        </div>
    );
}

export default Home;