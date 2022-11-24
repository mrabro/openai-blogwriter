module.exports = {
	entry: __dirname+'/admin/js/openai-blog-writer-admin.js',
	output: {
		path: __dirname,
		filename: 'admin/js/block.build.js',
	},
	module: {
		loaders: [
			{
				test: /.js$/,
				loader: 'babel-loader',
				exclude: /node_modules/,
			},
		],
	},
};