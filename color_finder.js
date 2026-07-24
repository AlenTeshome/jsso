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
const colors = new Set();
const fileMap = {};

const regex = /(#[0-9a-fA-F]{3,6}|rgba?\([^)]+\)|hsla?\([^)]+\))/g;

files.forEach(file => {
  const content = fs.readFileSync(file, 'utf8');
  let match;
  while ((match = regex.exec(content)) !== null) {
    const color = match[1].toLowerCase().replace(/\s+/g, '');
    colors.add(color);
    if (!fileMap[color]) fileMap[color] = [];
    if (!fileMap[color].includes(file)) fileMap[color].push(file);
  }
});

fs.writeFileSync('/home/alent/projects/jsso/color_report.json', JSON.stringify({
  colors: Array.from(colors),
  fileMap
}, null, 2));

console.log('Report generated at color_report.json');
