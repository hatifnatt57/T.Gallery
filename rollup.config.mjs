import terser from '@rollup/plugin-terser';
import commonjs from '@rollup/plugin-commonjs';
import { nodeResolve } from '@rollup/plugin-node-resolve';

export default {
	input: './node_modules/hyphen/ru/index.js',
	output: {
		file: './libs/hyphen.min.js',
		format: 'es'
	},
  plugins: [nodeResolve(), commonjs(), terser({ format: { comments: 'all' } })]
};
