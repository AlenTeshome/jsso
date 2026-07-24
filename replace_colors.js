const fs = require('fs');
const path = require('path');

function walk(dir, results = []) {
  const list = fs.readdirSync(dir);
  list.forEach((file) => {
    file = dir + '/' + file;
    const stat = fs.statSync(file);
    if (stat && stat.isDirectory()) { 
      walk(file, results);
    } else { 
      if (file.endsWith('.scss')) results.push(file);
    }
  });
  return results;
}

const files = walk('/home/alent/projects/jsso/web/themes/custom/jssotheme/scss');

const hexMap = {
    // Whites
    '#fff': 'var(--clr-bg-300)',
    '#ffffff': 'var(--clr-bg-300)',
    'rgb(255, 255, 255)': 'var(--clr-bg-300)',
    'rgb(255,255,255)': 'var(--clr-bg-300)',
    'hsl(0, 0%, 100%)': 'var(--clr-bg-300)',
    'hsl(0,0%,100%)': 'var(--clr-bg-300)',
    // Blacks
    '#000': 'var(--clr-bg-500)',
    '#000000': 'var(--clr-bg-500)',
    'hsl(0deg 0% 0%)': 'var(--clr-bg-500)',
    'hsl(0deg0%0%)': 'var(--clr-bg-500)',
    'hsl(0, 0%, 0%)': 'var(--clr-bg-500)',
    'hsl(0,0%,0%)': 'var(--clr-bg-500)',
    // Dark grays
    '#333': 'var(--clr-brand-jsso-text)',
    '#333333': 'var(--clr-brand-jsso-text)',
    '#444': 'var(--clr-brand-jsso-text)',
    '#555': 'var(--clr-brand-jsso-text)',
    '#666': 'var(--clr-brand-jsso-text)',
    '#666666': 'var(--clr-brand-jsso-text)',
    '#1a1a1a': 'var(--clr-brand-jsso-text)',
    '#212529': 'var(--clr-brand-jsso-text)',
    '#535151': 'var(--clr-brand-jsso-text)',
    // Light grays
    '#f8f9fa': 'var(--clr-brand-jsso-bluebeige)',
    '#f1f3f5': 'var(--clr-brand-jsso-bluebeige)',
    '#f5f5f5': 'var(--clr-brand-jsso-bluebeige)',
    '#f9f9f9': 'var(--clr-brand-jsso-bluebeige)',
    '#e4e4e4': 'var(--clr-brand-jsso-bluebeige)',
    '#f0f2f9': 'var(--clr-brand-jsso-bluebeige)',
    '#dee2e6': 'var(--clr-brand-jsso-bluebeige)',
    '#ccc': 'var(--clr-brand-jsso-bluebeige)',
    '#cccccc': 'var(--clr-brand-jsso-bluebeige)',
    '#6c757d': 'var(--clr-brand-jsso-bluebeige)',
    '#e0e0e0': 'var(--clr-brand-jsso-bluebeige)',
    '#ddd': 'var(--clr-brand-jsso-bluebeige)',
    '#dddddd': 'var(--clr-brand-jsso-bluebeige)',
    '#f0f4f8': 'var(--clr-brand-jsso-bluebeige)',
    '#f1f1f1': 'var(--clr-brand-jsso-bluebeige)',
    '#fafafa': 'var(--clr-brand-jsso-bluebeige)',
    // Primary brand legacy
    '#1c4b42': 'var(--clr-brand-jsso-primary)',
    '#0a1642': 'var(--clr-brand-jsso-primary)',
    '#0a3a60': 'var(--clr-brand-jsso-primary)',
    '#00104a': 'var(--clr-brand-jsso-primary)',
    '#e00000': 'var(--clr-brand-jsso-primary)',
    '#2a6f62': 'var(--clr-brand-jsso-primary)',
    // Secondary brand legacy
    '#0964fc': 'var(--clr-brand-jsso-secondary)',
    '#0056b3': 'var(--clr-brand-jsso-secondary)',
    '#0d6efd': 'var(--clr-brand-jsso-secondary)',
    '#2563eb': 'var(--clr-brand-jsso-secondary)',
    '#0073e6': 'var(--clr-brand-jsso-secondary)',
    '#4276fb': 'var(--clr-brand-jsso-secondary)'
};

let filesChanged = 0;

files.forEach(file => {
    // Skip variables files so we don't destroy definitions
    if (file.endsWith('_variables.scss') || file.endsWith('_bootstrap-variables.scss') || file.endsWith('_variables_drupal.scss') || file.endsWith('_variables_bootstrap.scss')) {
        return;
    }

    let content = fs.readFileSync(file, 'utf8');
    let originalContent = content;

    // Replace RGBA (for both shadows and backgrounds)
    content = content.replace(/rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*,\s*([\d.]+)\s*\)/gi, (match, p1) => {
        const alpha = parseFloat(p1) * 100;
        return `color-mix(in srgb, var(--clr-brand-jsso-secondary) ${alpha}%, transparent)`;
    });

    // Replace specific Hex/RGB/HSL from our map
    // We use a regex that safely matches hex codes without matching subsets (using negative lookahead for valid hex chars)
    for (const [key, val] of Object.entries(hexMap)) {
        if (key.startsWith('#')) {
            const regex = new RegExp(key + '(?![0-9a-fA-F])', 'gi');
            content = content.replace(regex, val);
        } else {
            // For rgb/hsl, we escape parentheses
            const escapedKey = key.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const regex = new RegExp(escapedKey, 'gi');
            content = content.replace(regex, val);
        }
    }

    if (content !== originalContent) {
        fs.writeFileSync(file, content, 'utf8');
        filesChanged++;
    }
});

console.log(`Updated ${filesChanged} SCSS files.`);
