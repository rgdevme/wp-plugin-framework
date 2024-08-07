const cmd = require('child_process')
const fs = require('node:fs')
const pkg = require('./composer.json')
const { version } = require('os')

let [major, minor, patch] = (pkg.version ?? '0.0.0').split('.').map(x => Number(x))

const scope = process.argv[2].split('=')[1]

switch (scope) {
  case 'major':
    major += 1
    break
  case 'minor':
    minor += 1
    break
  case 'patch':
    patch += 1
    break
  default:
    break
}

const v = `${major}.${minor}.${patch}`
pkg.version = v

try {
  fs.writeFileSync(
    './composer.json',
    JSON.stringify(pkg, null, 2)
  )
  console.log(`Version bumped to ${v}`)
  cmd.execSync(`git add *`)
  cmd.execSync(`git commit -m "Version bumped to ${v}"`)
  cmd.execSync('git push')
  console.log('Changes pushed. Creating release...')
  cmd.execSync(`gh release create v${v}`)
  console.log('Release created')
} catch (error) {
  console.error(error)

}
