# enable RubyInstaller DevKit usage as a vendorable helper library
unless ENV['PATH'].include?('C:\\Bitnami\\progresso2\\DevKit\\mingw\\bin') then
  puts 'Temporarily enhancing PATH to include DevKit...'
  ENV['PATH'] = 'C:\\Bitnami\\progresso2\\DevKit\\bin;C:\\Bitnami\\progresso2\\DevKit\\mingw\\bin;' + ENV['PATH']
end
ENV['RI_DEVKIT'] = 'C:\\Bitnami\\progresso2\\DevKit'
ENV['CC'] = 'gcc'
ENV['CXX'] = 'g++'
ENV['CPP'] = 'cpp'
