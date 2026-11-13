"""Concatene et minifie les sources CSS -> css/ganho.css (site public) et css/gn-app.css (admin + espace client). Sans dependance."""
import re, pathlib
here = pathlib.Path(__file__).parent


def bundle(names, target):
    css = '\n'.join((here / 'css' / 'src' / f'{n}.css').read_text(encoding='utf8') for n in names)
    css = re.sub(r'/\*.*?\*/', '', css, flags=re.S)
    css = re.sub(r'\s+', ' ', css)
    css = re.sub(r'\s*([{};,>])\s*', r'\1', css)
    css = re.sub(r';}', '}', css)
    (here / 'css' / target).write_text(css, encoding='utf8')
    print(target, len(css), 'octets')


bundle(['fonts', 'tokens', 'components', 'home', 'install'], 'ganho.css')
bundle(['fonts', 'tokens', 'app', 'admin', 'install'], 'gn-app.css')
