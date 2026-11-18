document.addEventListener('DOMContentLoaded', async () => {
    let anjungan_uuid = localStorage.getItem('anjungan_uuid');

    // ------------------------------------------------------------------
    // 1. Kalau UUID sudah ada
    // ------------------------------------------------------------------
    if (anjungan_uuid) {
        console.log("UUID terverifikasi pada server:", anjungan_uuid);
    }

    // ------------------------------------------------------------------
    // 2. Kalau UUID belum ada → generate baru dan tampilkan pada form
    // ------------------------------------------------------------------
    if (!anjungan_uuid) {
        getIp();
        getUserAgent();
        const temp_uuid = crypto.randomUUID();
        $('#anjungan_id').val(temp_uuid);
    }
});

getIp = () => {
    fetch('https://api.ipify.org?format=json') 
    .then(r => r.json())
    .then(
        d => {
            document.getElementById('ip_address').value = d.ip;
        }
    );
};

getUserAgent = () => {
    const ua = navigator.userAgent;

    const isMobile = /Mobi|Android|iPhone|iPad/i.test(ua);
    const isRobot = /bot|crawl|slurp|spider|mediapartners/i.test(ua);

    let browser = 'Unknown';
    let version = 'Unknown';
    let platform = 'Unknown';

    // Platform
    if (/Windows NT/i.test(ua)) platform = 'Windows';
    else if (/Mac OS X/i.test(ua)) platform = 'macOS';
    else if (/Android/i.test(ua)) platform = 'Android';
    else if (/iPhone|iPad/i.test(ua)) platform = 'iOS';
    else if (/Linux/i.test(ua)) platform = 'Linux';

    // Browser
    const browserRegex = [
        [/Edg\/([\d.]+)/, 'Edge'],
        [/Chrome\/([\d.]+)/, 'Chrome'],
        [/Firefox\/([\d.]+)/, 'Firefox'],
        [/Safari\/([\d.]+)/, 'Safari'],
    ];

    for (const [regex, name] of browserRegex) {
        const match = ua.match(regex);
        if (match) {
            browser = name;
            version = match[1];
            break;
        }
    }

    let result = {
        browser,
        version,
        platform,
        is_mobile: isMobile,
        is_robot: isRobot,
        is_browser: !isRobot,
        user_agent_string: ua
    };

    document.getElementById('user_agent').value = JSON.stringify(result)
}