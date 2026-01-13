{{--
    Email Obfuscation Component
    Usage: <x-obfuscated-email email="info" domain="arsus.nl" classes="text-gray-400 hover:text-[#fe742b]" />
--}}

@props(['email', 'domain', 'classes' => ''])

<a
    href="#"
    class="{{ $classes }} transition-colors"
    data-email="{{ $email }}"
    data-domain="{{ $domain }}"
    onclick="revealEmail(this); return false;"
    style="unicode-bidi: bidi-override; direction: rtl;"
>
    {{ strrev('liame laever ot kcilC') }}
</a>

<script>
if (typeof revealEmail === 'undefined') {
    function revealEmail(element) {
        const email = element.getAttribute('data-email');
        const domain = element.getAttribute('data-domain');
        const fullEmail = email + '@' + domain;

        element.href = 'mailto:' + fullEmail;
        element.textContent = fullEmail;
        element.style.unicodeBidi = 'normal';
        element.style.direction = 'ltr';
        element.onclick = null;

        window.location.href = element.href;
    }
}
</script>
